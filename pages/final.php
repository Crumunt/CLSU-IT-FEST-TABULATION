<?php
session_start();
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
$top_participants = $pdo->query("SELECT * FROM finalparticipants ORDER BY gender ASC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories WHERE category_id IN (5, 7, 8)")->fetchAll(PDO::FETCH_ASSOC);

$judge_id = $_SESSION['judge_id'];
$stmt = $pdo->prepare("SELECT * FROM finals WHERE judge_id = ?");
$stmt->execute([$judge_id]);
$existing_scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$scores_by_participant = [];
foreach ($existing_scores as $score) {
  $scores_by_participant[$score['participant_id']][$score['category_id']] = $score['score'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scores'])) {
  foreach ($_POST['scores'] as $participant_id => $scores) {
    foreach ($scores as $category_id => $score) {
      $stmt = $pdo->prepare("INSERT INTO finals (judge_id, participant_id, category_id, score) 
                                        VALUES (?, ?, ?, ?) 
                                        ON DUPLICATE KEY UPDATE score = VALUES(score)");
      $stmt->execute([$judge_id, $participant_id, $category_id, $score]);
    }
  }
  echo "<div class='alert alert-success text-center'>Scores submitted successfully!</div>";
  header("Location: end.php");
  exit;
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

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

  <div class="container mt-4">
    <form method="post">

      <!-- Submit Confirmation Modal -->
      <div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="submitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="submitModalLabel">Confirm Score Finalization</h5>
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

      <div class="d-flex flex-row justify-content-around">
        <div>
          <div class="d-flex flex-row justify-content-between align-items-center pb-3">
            <h5 class="mb-0">Final Stage</h5>
            <button type="button" class="buttonColor btn" data-bs-toggle="modal" data-bs-target="#submitModal">Finalize Scores</button>
          </div>

          <table class="table table-bordered table-hover">
            <thead class="tableHead1 text-center">
              <tr>
                <td colspan="8" class="text-center fw-bold bg-light">Female Candidates</td>
              </tr>
              <tr>
                <th>#</th>
                <th>Participant</th>
                <th>College</th>
                <th>Gender</th>
                <th>Beauty of Face (40)</th>
                <th>Ability to Answer (40)</th>
                <th>Overall Appeal (20)</th>
              </tr>
            </thead>
            <tbody class="tableBody">
              <?php
              $last_gender = ''; // Initialize last gender variable

              foreach ($top_participants as $participant):
                // Gender divider logic
                if ($last_gender !== '' && $last_gender !== $participant['gender']): ?>
                  <thead class="tableHead1 text-center">
                    <tr>
                      <td colspan="8" class="text-center fw-bold bg-light">Male Candidates</td>
                    </tr>
                    <tr>
                      <th>#</th>
                      <th>Participant</th>
                      <th>College</th>
                      <th>Gender</th>
                      <th>Beauty of Face (40)</th>
                      <th>Ability to Answer (40)</th>
                      <th>Overall Appeal (20)</th>
                    </tr>
                  </thead>
                <?php endif;

                $last_gender = $participant['gender']; // Update the last gender variable
                ?>
                <tr class="tableRow">
                  <td><?= htmlspecialchars($participant['participant_num']) ?></td>
                  <td><?= htmlspecialchars($participant['participant_name']) ?></td>
                  <td><?= htmlspecialchars($participant['college']) ?></td>
                  <td><?= htmlspecialchars($participant['gender']) ?></td>

                  <td>
                    <input type="number" class="form-control" name="scores[<?= $participant['participant_id'] ?>][5]" min="1" max="40"
                      value="<?= isset($scores_by_participant[$participant['participant_id']][5]) ? $scores_by_participant[$participant['participant_id']][5] : '' ?>" placeholder="0" step="1">
                  </td>
                  <td>
                    <input type="number" class="form-control" name="scores[<?= $participant['participant_id'] ?>][7]" min="1" max="40"
                      value="<?= isset($scores_by_participant[$participant['participant_id']][7]) ? $scores_by_participant[$participant['participant_id']][7] : '' ?>" placeholder="0" step="1">
                  </td>

                  <td>
                    <input type="number" class="form-control" name="scores[<?= $participant['participant_id'] ?>][8]" min="1" max="20"
                      value="<?= isset($scores_by_participant[$participant['participant_id']][8]) ? $scores_by_participant[$participant['participant_id']][8] : '' ?>" placeholder="0" step="1">
                  </td>
                </tr>
              <?php endforeach; ?>

            </tbody>
          </table>

        </div>
      </div>
    </form>

  </div>
</body>
<script type="module" src="../bootstrap/js/bootstrap.bundle.min.js"></script>

</html>