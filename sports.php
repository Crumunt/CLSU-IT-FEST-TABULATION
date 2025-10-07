<?php
session_start();
$host = 'localhost';
$dbname = 'tabulation';
$username = 'root';
$password = '';
$port = '3307';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Fetch participants and categories
$participants = $pdo->query("SELECT * FROM participants ORDER BY participant_num ASC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories WHERE category_id IN (1, 5, 6, 7, 8)")->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing scores for the logged-in judge
$judge_id = $_SESSION['judge_id'];
$stmt = $pdo->prepare("SELECT * FROM scores WHERE judge_id = ?");
$stmt->execute([$judge_id]);
$existing_scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize the scores for easier access
$scores_by_participant = [];
foreach ($existing_scores as $score) {
    $scores_by_participant[$score['participant_id']][$score['category_id']] = $score['score'];
}

// Handle form submission to save scores
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scores'])) {
    foreach ($_POST['scores'] as $participant_id => $scores) {
        foreach ($scores as $category_id => $score) {
            $stmt = $pdo->prepare("INSERT INTO scores (judge_id, participant_id, category_id, score) 
                                    VALUES (?, ?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE score = VALUES(score)");
            $stmt->execute([$judge_id, $participant_id, $category_id, $score]);
            header("Location: uniform.php");
        }
    }
    echo "<div class='alert alert-success text-center'>Scores submitted successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../designss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<style>
    .candidate-card {
        max-width: 800px;
        margin: 0 auto;
    }

    .candidate-image {
        width: 100%;
        max-width: 400px;
        height: 500px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .progress-container {
        margin-bottom: 30px;
    }

    .step-indicator {
        font-size: 1.2rem;
        color: #6c757d;
    }

    .criteria-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .score-input-group {
        margin-bottom: 15px;
    }

    .score-input-group label {
        font-weight: 600;
        margin-bottom: 5px;
        display: block;
    }

    .score-input-group input {
        font-size: 1.1rem;
    }

    .section-divider {
        margin: 30px 0;
        border-top: 3px solid #dee2e6;
    }

    .buttonColor {
        background-color: #0d6efd;
        color: white;
    }

    .buttonColor:hover {
        background-color: #0b5ed7;
        color: white;
    }

    .summary-table {
        font-size: 0.9rem;
    }

    .summary-table input {
        font-size: 0.85rem;
        padding: 4px 8px;
    }

    .edit-mode-badge {
        background-color: #ffc107;
        color: #000;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
    }
</style>

<body>

    <div class="d-flex align-items-center justify-content-between py-3 px-3 border-bottom head">
        <div class="d-flex align-items-center">
            <img src="../assets/logo.png" alt="CLSU Logo" class="imgLogo me-3">
            <h2 class="headTitle mb-0">CLSU Pageant Tabulation System</h2>
        </div>

        <div class="d-flex align-items-center">
            <h2 class="text-center mb-0 me-3 headTitle">Judge <?= $_SESSION['judge_id'] ?></h2>
            <form action="logout.php" method="post">
                <button class="btn headButton" name="logout" type="submit">
                    EXIT
                </button>
            </form>
        </div>
    </div>


    <div class="container py-4">
        <form method="post" id="scoringForm">

            <!-- Step 1: Individual Scoring -->
            <div id="stepScoring" class="scoring-step">
                <div class="progress-container">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0">Score Candidates</h4>
                        <span class="step-indicator">
                            Candidate <span id="currentStep">1</span> of <span id="totalSteps">0</span>
                        </span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>

                <div class="candidate-card">
                    <div class="card">
                        <div class="card-body shadow-md">
                            <div class="row">
                                <div class="col-md-5 text-center">
                                    <div class="candidate-img mb-3">
                                        <img id="candidateImage" src="../assets/candidates/tmp_candidate.jpg"
                                            alt="Candidate Photo" class="candidate-image">
                                    </div>
                                    <div class="candidate-info">
                                        <h3 id="candidateNumber" class="mb-2">#</h3>
                                        <h4 id="candidateName" class="mb-1"></h4>
                                        <p id="candidateCollege" class="text-muted mb-1"></p>
                                        <p id="candidateGender" class="text-muted"></p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h5 class="mb-3">Score this Candidate</h5>

                                    <!-- sports Score -->
                                    <div class="criteria-section mb-3">
                                        <h6 class="mb-3">Best in Sports Attire</h6>
                                        <div class="score-input-group">
                                            <label for="sports">Sports (Max: 15)</label>
                                            <input type="number" class="form-control" id="sports" min="1" max="15"
                                                step="1" placeholder="Enter score">
                                        </div>
                                    </div>

                                    <div class="section-divider"></div>

                                    <!-- Preliminary Criteria -->
                                    <div class="criteria-section">
                                        <h6 class="mb-3">Preliminary Criteria</h6>

                                        <div class="score-input-group">
                                            <label for="beautyOfFace">Clarity of Speech and Diction (Max: 10)</label>
                                            <input type="number" class="form-control" id="beautyOfFace" min="1" max="40"
                                                step="1" placeholder="Enter score">
                                        </div>

                                        <div class="score-input-group">
                                            <label for="stageProjection">Content and Relevance of Self-Introduction (Max: 10)</label>
                                            <input type="number" class="form-control" id="stageProjection" min="1"
                                                max="30" step="1" placeholder="Enter score">
                                        </div>

                                        <div class="score-input-group">
                                            <label for="abilityToAnswer">Authenticity, Personality, and Charisma (Max: 10)</label>
                                            <input type="number" class="form-control" id="abilityToAnswer" min="1"
                                                max="20" step="1" placeholder="Enter score">
                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-secondary" id="backBtn" disabled>
                                            ← Back
                                        </button>
                                        <button type="button" class="btn buttonColor btn-lg" id="nextBtn">
                                            Next Candidate →
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Summary Review -->
            <div id="stepSummary" class="summary-step" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Review Your Scores</h4>
                        <p class="text-muted mb-0">You can edit any scores before final submission</p>
                    </div>
                    <span class="edit-mode-badge">✏️ Edit Mode</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Best in Sports Attire</h5>
                        <table class="table table-bordered table-hover summary-table mt-4">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Pariticipant Number</th>
                                    <th>Participant</th>
                                    <th>Element</th>
                                    <th>Gender</th>
                                    <th>Best in Production (15)</th>
                                </tr>
                            </thead>
                            <tbody id="summarySportsTable"></tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Preliminary Criteria</h5>
                        <table class="table table-bordered table-hover summary-table">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Clarity of Speech and Diction (10)</th>
                                    <th>Content and Relevance of Self-Introduction (10)</th>
                                    <th>Authenticity, Personality, and Charisma (10)</th>
                                </tr>
                            </thead>
                            <tbody id="summaryPrelimTable"></tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="backToScoringBtn">
                        ← Back to Scoring
                    </button>
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal"
                        data-bs-target="#submitModal">
                        Submit Final Scores
                    </button>
                </div>
            </div>

            <!-- Submit Confirmation Modal -->
            <div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="submitModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="submitModalLabel">Confirm Final Submission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to submit these scores? Once submitted, they cannot be changed.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Yes, Submit Scores</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</body>
<script type="module" src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    // Sample participant data - Replace with PHP data
    const participants = [
        <?php foreach ($participants as $index => $participant): ?>
                {
                id: <?= $participant['participant_id'] ?>,
                number: '<?= htmlspecialchars($participant['participant_num']) ?>',
                name: '<?= htmlspecialchars($participant['participant_name']) ?>',
                college: '<?= htmlspecialchars($participant['college']) ?>',
                gender: '<?= htmlspecialchars($participant['gender']) ?>',
                image: '../assets/candidates/<?= trim($participant['participant_img_name']) ?>'
            }<?= $index < count($participants) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
    ];

    // Load existing scores if any
    const existingScores = {};
    <?php if (isset($scores_by_participant)): ?>
        <?php foreach ($scores_by_participant as $participantId => $scores): ?>
            existingScores[<?= $participantId ?>] = {
                sports: <?= isset($scores[2]) ? $scores[2] : 'null' ?>,
                beautyOfFace: <?= isset($scores[8]) ? $scores[8] : 'null' ?>,
                stageProjection: <?= isset($scores[9]) ? $scores[9] : 'null' ?>,
                abilityToAnswer: <?= isset($scores[10]) ? $scores[10] : 'null' ?>
            };
        <?php endforeach; ?>
    <?php endif; ?>

    let currentIndex = 0;
    let scores = {};

    // Initialize scores object
    participants.forEach(p => {
        scores[p.id] = existingScores[p.id] || {
            sports: null,
            beautyOfFace: null,
            stageProjection: null,
            abilityToAnswer: null
        }
    });

    function updateProgress() {
        const progress = ((currentIndex + 1) / participants.length) * 100;
        document.getElementById('progressBar').style.width = progress + '%';
        document.getElementById('currentStep').textContent = currentIndex + 1;
        document.getElementById('totalSteps').textContent = participants.length;
    }

    function loadCandidate(index) {
        const participant = participants[index];
        document.getElementById('candidateImage').src = participant.image;
        document.getElementById('candidateNumber').textContent = '#' + participant.number;
        document.getElementById('candidateName').textContent = participant.name;
        document.getElementById('candidateCollege').textContent = participant.college;
        document.getElementById('candidateGender').textContent = participant.gender;

        // Load saved scores
        const savedScores = scores[participant.id];
        document.getElementById('sports').value = savedScores.sports || '';
        document.getElementById('beautyOfFace').value = savedScores.beautyOfFace || '';
        document.getElementById('stageProjection').value = savedScores.stageProjection || '';
        document.getElementById('abilityToAnswer').value = savedScores.abilityToAnswer || '';

        updateProgress();

        // Update back button state
        document.getElementById('backBtn').disabled = index === 0;
    }

    function saveCurrentScores() {
        const participant = participants[currentIndex];
        scores[participant.id] = {
            sports: document.getElementById('sports').value || null,
            beautyOfFace: document.getElementById('beautyOfFace').value || null,
            stageProjection: document.getElementById('stageProjection').value || null,
            abilityToAnswer: document.getElementById('abilityToAnswer').value || null
        };
    }

    function showSummary() {
        document.getElementById('stepScoring').style.display = 'none';
        document.getElementById('stepSummary').style.display = 'block';

        populateSummaryTables();
    }

    function populateSummaryTables() {
        const sportsTableBody = document.getElementById('summarySportsTable');
        const prelimTableBody = document.getElementById('summaryPrelimTable');

        sportsTableBody.innerHTML = '';
        prelimTableBody.innerHTML = '';

        let lastGender = '';
        participants.forEach(participant => {
            const score = scores[participant.id];

            // Add gender divider if needed
            if (lastGender && lastGender !== participant.gender) {
                const dividerRow1 = `<tr class="table-secondary"><td colspan="5" class="text-center fw-bold">Female Candidates</td></tr>`;
                const dividerRow2 = `<tr class="table-secondary"><td colspan="4" class="text-center fw-bold">Female Candidates</td></tr>`;
                sportsTableBody.innerHTML += dividerRow1;
                prelimTableBody.innerHTML += dividerRow2;
            }
            lastGender = participant.gender;

            // sports table row
            const sportsRow = `
                    <tr>
                        <td>${participant.number}</td>
                        <td>${participant.name}</td>
                        <td>${participant.college}</td>
                        <td>${participant.gender}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   name="scores[${participant.id}][2]" 
                                   min="1" max="15" step="1" 
                                   value="${score.sports || ''}" 
                                   placeholder="0">
                        </td>
                    </tr>
                `;
            sportsTableBody.innerHTML += sportsRow;

            // Preliminary table row
            const prelimRow = `
                    <tr>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   name="scores[${participant.id}][8]" 
                                   min="1" max="10" step="1" 
                                   value="${score.beautyOfFace || ''}" 
                                   placeholder="0">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   name="scores[${participant.id}][9]" 
                                   min="1" max="10" step="1" 
                                   value="${score.stageProjection || ''}" 
                                   placeholder="0">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   name="scores[${participant.id}][10]" 
                                   min="1" max="10" step="1" 
                                   value="${score.abilityToAnswer || ''}" 
                                   placeholder="0">
                        </td>
                    </tr>
                `;
            prelimTableBody.innerHTML += prelimRow;
        });
    }

    console.log('happening')
    // Event Listeners
    document.getElementById('nextBtn').addEventListener('click', () => {
        saveCurrentScores();

        if (currentIndex < participants.length - 1) {
            currentIndex++;
            loadCandidate(currentIndex);
        } else {
            showSummary();
        }
    });

    document.getElementById('backBtn').addEventListener('click', () => {
        saveCurrentScores();
        if (currentIndex > 0) {
            currentIndex--;
            loadCandidate(currentIndex);
        }
    });

    document.getElementById('backToScoringBtn').addEventListener('click', () => {
        document.getElementById('stepSummary').style.display = 'none';
        document.getElementById('stepScoring').style.display = 'block';
    });

    // Initialize
    loadCandidate(0);
</script>

</html>