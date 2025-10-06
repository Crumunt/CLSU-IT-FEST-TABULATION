<?php
$host = 'localhost';
$dbname = 'pageant1';
$username = 'root';
$password = '';
$port = '3307';     

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// $top_participants = $pdo->query("SELECT * FROM finalparticipants ORDER BY gender ASC, participant_num ASC")->fetchAll(PDO::FETCH_ASSOC);
$participants = $pdo->query("SELECT * FROM top_3_participants ORDER BY gender ASC")->fetchAll(PDO::FETCH_ASSOC);

$maleParticipants = [];
$femaleParticipants = [];
foreach ($participants as $participant) {
    if (strtolower($participant['gender']) == 'male') {
        $maleParticipants[] = [
            'id' => $participant['participant_id'],
            'name' => $participant['participant_name'],
            'score' => $participant['avg_total_score'],
            'ranking' => $participant['avg_gender_ranking']
        ];
    } elseif (strtolower($participant['gender']) == 'female') {
        $femaleParticipants[] = [
            'id' => $participant['participant_id'],
            'name' => $participant['participant_name'],
            'score' => $participant['avg_total_score'],
            'ranking' => $participant['avg_gender_ranking']
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ids = array_merge($_POST['female_finalists'], $_POST['male_finalists']);

    if (!empty($ids) && is_array($ids)) {
        // Create placeholders for prepared statement
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "INSERT INTO finalparticipants(participant_id, participant_num, participant_name, gender, college, participant_img_name) SELECT participant_id, participant_num, participant_name, gender, college, participant_img_name FROM participants WHERE participant_id IN ($placeholders)";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($ids)) {
            header('Location: index.php?message=' . urlencode('Participants added successfully!'));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #718096;
            font-size: 1.1rem;
        }

        .tables-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .selection-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody tr {
            border-bottom: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        tbody tr.selected {
            background: #f0fdf4;
        }

        tbody tr.selected:hover {
            background: #dcfce7;
        }

        tbody tr.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        td {
            padding: 1rem 1.5rem;
        }

        .checkbox-cell {
            width: 60px;
        }

        .custom-checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid #cbd5e0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .selected .custom-checkbox {
            background: #22c55e;
            border-color: #22c55e;
        }

        .custom-checkbox svg {
            display: none;
            width: 16px;
            height: 16px;
            color: white;
        }

        .selected .custom-checkbox svg {
            display: block;
        }

        .participant-name {
            font-weight: 500;
            color: #1a202c;
        }

        .score {
            font-weight: 600;
            color: #1a202c;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #d1fae5;
            color: #065f46;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            border: 2px solid #22c55e;
            display: none;
            margin-bottom: 2rem;
        }

        .summary-card.show {
            display: block;
        }

        .summary-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-header h3 {
            font-size: 1.75rem;
            color: #1a202c;
        }

        .summary-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .finalist-section h4 {
            font-size: 1.25rem;
            color: #374151;
            margin-bottom: 1rem;
        }

        .finalist-list {
            list-style: none;
        }

        .finalist-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #eff6ff;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .finalist-section.female .finalist-item {
            background: #fce7f3;
        }

        .finalist-rank {
            width: 32px;
            height: 32px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .finalist-section.female .finalist-rank {
            background: #ec4899;
        }

        .finalist-info {
            flex: 1;
        }

        .finalist-name {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
        }

        .finalist-score {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .icon {
            width: 24px;
            height: 24px;
        }

        .submit-section {
            text-align: center;
            padding: 2rem 0;
        }

        .submit-btn {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: none;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(34, 197, 94, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(34, 197, 94, 0.4);
        }

        .submit-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .error-message {
            color: #dc2626;
            background: #fee2e2;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            display: none;
        }

        .error-message.show {
            display: block;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin Panel</h2>
        <div class="text-center mb-4">
            <a href="production.php" class="btn btn-success btn-lg mb-3">Get Best in Production</a>
            <a href="uniform.php" class="btn btn-success btn-lg mb-3">Get Best in Uniform</a>
            <a href="casual.php" class="btn btn-success btn-lg mb-3">Get Best in Casual</a>
            <a href="formal.php" class="btn btn-success btn-lg mb-3">Get Best in Formal</a>
        </div>
        <div class="text-center">
            <a href="finalthree.php" class="btn btn-warning btn-lg mb-3">Get Best Final Three</a>
            <a href="grand.php" class="btn btn-warning btn-lg mb-3">Get Grand Winner</a>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Finalist Selection System</h1>
            <p>Select 3 finalists for each gender category</p>
        </div>

        <form id="finalistForm" method="POST" action="">
            <div class="tables-container">
                <div class="table-card">
                    <div class="table-header">
                        <h2>
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Male Participants
                        </h2>
                        <div class="selection-badge">
                            <span id="male-count">0</span>/3 Selected
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Participant Name</th>
                                <th>Average Total Score</th>
                                <th>Average Gender Ranking</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="male-tbody"></tbody>
                    </table>
                </div>

                <div class="table-card">
                    <div class="table-header">
                        <h2>
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Female Participants
                        </h2>
                        <div class="selection-badge">
                            <span id="female-count">0</span>/3 Selected
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Participant Name</th>
                                <th>Average Total Score</th>
                                <th>Average Gender Ranking</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="female-tbody"></tbody>
                    </table>
                </div>
            </div>

            <div class="summary-card" id="summary-card">
                <div class="summary-header">
                    <svg class="icon" style="width: 32px; height: 32px; color: #22c55e;" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <h3>Selected Finalists</h3>
                </div>
                <div class="summary-content">
                    <div class="finalist-section male">
                        <h4>Male Finalists</h4>
                        <ol class="finalist-list" id="male-finalists"></ol>
                    </div>
                    <div class="finalist-section female">
                        <h4>Female Finalists</h4>
                        <ol class="finalist-list" id="female-finalists"></ol>
                    </div>
                </div>
            </div>

            <div class="error-message" id="error-message">
                Please select exactly 3 finalists from each gender category before submitting.
            </div>

            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success text-center"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php endif; ?>

            <div class="submit-section">
                <button type="submit" class="submit-btn" id="submit-btn" disabled>
                    Submit Finalists
                </button>
            </div>
        </form>
    </div>


    <script type="module" src="../bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        const maleParticipants = <?= json_encode($maleParticipants) ?>;

        const femaleParticipants = <?= json_encode($femaleParticipants) ?>;

        const maleFinalists = [];
        const femaleFinalists = [];

        function renderTable(participants, tbodyId, gender) {
            const tbody = document.getElementById(tbodyId);
            tbody.innerHTML = '';

            participants.forEach(p => {
                const tr = document.createElement('tr');
                tr.dataset.id = p.id;
                tr.dataset.gender = gender;
                tr.dataset.name = p.name;
                tr.dataset.score = p.score;
                tr.dataset.ranking = p.ranking

                tr.innerHTML = `
                    <td class="checkbox-cell">
                        <div class="custom-checkbox">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </td>
                    <td><div class="participant-name">${p.name}</div></td>
                    <td><div class="score">${p.score}</div></td>
                    <td><div class="ranking">${p.ranking}</div></td>
                    <td><span class="status-badge" style="display: none;">Finalist</span></td>
                `;

                tr.addEventListener('click', () => {
                    if (!tr.classList.contains('disabled')) {
                        toggleFinalist(tr);
                    }
                });

                tbody.appendChild(tr);
            });
        }

        function updateCount(gender) {
            const count = gender === 'male' ? maleFinalists.length : femaleFinalists.length;
            document.getElementById(gender + '-count').textContent = count;
        }

        function updateRowStates(gender) {
            const tbody = document.getElementById(gender + '-tbody');
            const rows = tbody.querySelectorAll('tr');
            const finalists = gender === 'male' ? maleFinalists : femaleFinalists;

            rows.forEach(row => {
                const id = parseInt(row.dataset.id);
                const isSelected = finalists.includes(id);

                if (isSelected) {
                    row.classList.add('selected');
                    row.classList.remove('disabled');
                    row.querySelector('.status-badge').style.display = 'inline-block';
                } else {
                    row.classList.remove('selected');
                    row.querySelector('.status-badge').style.display = 'none';

                    if (finalists.length >= 3) {
                        row.classList.add('disabled');
                    } else {
                        row.classList.remove('disabled');
                    }
                }
            });
        }

        function updateSummary() {
            const summaryCard = document.getElementById('summary-card');
            const submitBtn = document.getElementById('submit-btn');
            const errorMsg = document.getElementById('error-message');

            if (maleFinalists.length === 3 && femaleFinalists.length === 3) {
                summaryCard.classList.add('show');
                submitBtn.disabled = false;
                errorMsg.classList.remove('show');

                const maleList = document.getElementById('male-finalists');
                maleList.innerHTML = '';
                const maleData = getSortedFinalists('male');
                maleData.forEach((finalist, index) => {
                    const li = document.createElement('li');
                    li.className = 'finalist-item';
                    li.innerHTML = `
                        <div class="finalist-rank">${index + 1}</div>
                        <div class="finalist-info">
                            <div class="finalist-name">${finalist.name}</div>
                            <div class="finalist-score">Score: ${finalist.score}</div>
                        </div>
                    `;
                    maleList.appendChild(li);
                });

                const femaleList = document.getElementById('female-finalists');
                femaleList.innerHTML = '';
                const femaleData = getSortedFinalists('female');
                femaleData.forEach((finalist, index) => {
                    const li = document.createElement('li');
                    li.className = 'finalist-item';
                    li.innerHTML = `
                        <div class="finalist-rank">${index + 1}</div>
                        <div class="finalist-info">
                            <div class="finalist-name">${finalist.name}</div>
                            <div class="finalist-score">Score: ${finalist.score}</div>
                        </div>
                    `;
                    femaleList.appendChild(li);
                });
            } else {
                summaryCard.classList.remove('show');
                submitBtn.disabled = true;
            }
        }

        function getSortedFinalists(gender) {
            const tbody = document.getElementById(gender + '-tbody');
            const finalists = gender === 'male' ? maleFinalists : femaleFinalists;

            return finalists
                .map(id => {
                    const row = tbody.querySelector(`tr[data-id="${id}"]`);
                    return {
                        id: id,
                        name: row.dataset.name,
                        score: parseFloat(row.dataset.score)
                    };
                })
                .sort((a, b) => b.score - a.score);
        }

        function toggleFinalist(row) {
            const id = parseInt(row.dataset.id);
            const gender = row.dataset.gender;
            const finalists = gender === 'male' ? maleFinalists : femaleFinalists;

            const index = finalists.indexOf(id);

            if (index > -1) {
                finalists.splice(index, 1);
            } else {
                if (finalists.length < 3) {
                    finalists.push(id);
                }
            }

            updateCount(gender);
            updateRowStates(gender);
            updateSummary();
        }

        document.getElementById('finalistForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (maleFinalists.length !== 3 || femaleFinalists.length !== 3) {
                document.getElementById('error-message').classList.add('show');
                return;
            }

            // Remove any existing hidden inputs
            const existingInputs = this.querySelectorAll('input[type="hidden"]');
            existingInputs.forEach(input => input.remove());

            // Add male finalists as hidden inputs
            maleFinalists.forEach((id, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `male_finalists[${index}]`;
                input.value = id;
                this.appendChild(input);
            });

            // Add female finalists as hidden inputs
            femaleFinalists.forEach((id, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `female_finalists[${index}]`;
                input.value = id;
                this.appendChild(input);
            });

            console.log('Form submitting with finalists:', { maleFinalists, femaleFinalists });

            // Now submit the form
            this.submit();
        });

        renderTable(maleParticipants, 'male-tbody', 'male');
        renderTable(femaleParticipants, 'female-tbody', 'female');
    </script>

</body>

</html>