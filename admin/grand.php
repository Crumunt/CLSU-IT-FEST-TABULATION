<?php
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

$query = "SELECT * FROM top_3_participants WHERE gender IN ('Male', 'Female') ORDER BY ranking_within_gender;";

$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Separate results into male and female arrays
$maleResults = [];
$femaleResults = [];

foreach ($results as $row) {
    if ($row['gender'] === 'Male') {
        $maleResults[] = $row;
    } else {
        $femaleResults[] = $row;
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
        /* Custom styles for larger cells */
        .big-cell {
            font-size: 1.5rem;
            /* Larger font size */
            font-weight: bold;
            /* Bold text */
            background-color: #f8d7da;
            /* Optional background color for emphasis */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Grand Winner</h2>

        <!-- Female Participants Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">Top Female Participants</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="tableHead text-center">
                        <tr>
                            <th>#</th>
                            <th>Element</th>
                            <th>Participant Name</th>
                            <!-- <th>Total Score</th> -->
                            <th>Average Total Score</th>
                            <th>Place</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody text-center">
                        <?php if (!empty($femaleResults)): ?>
                            <?php foreach ($femaleResults as $row): ?>
                                <tr>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['participant_num']) ?></td>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['college']) ?></td>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['participant_name']) ?></td>
                                    <!-- <td class="<?= $row['rranking_within_genderank'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['total_score'] / 5) ?></td> -->
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['avg_total_score']) ?></td>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['ranking_within_gender']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No female results found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Male Participants Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">Top Male Participants</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="tableHead text-center">
                        <tr>
                            <th>#</th>
                            <th>Element</th>
                            <th>Participant</th>
                            <!-- <th>Total Score</th> -->
                            <th>Average Total Score</th>
                            <th>Place</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody text-center">
                        <?php if (!empty($maleResults)): ?>
                            <?php foreach ($maleResults as $row): ?>
                                <tr>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['participant_num']) ?></td>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['college']) ?></td>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['participant_name']) ?></td>
                                    <!-- <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['total_score'] / 5) ?></td> -->
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['avg_total_score']) ?></td>
                                    <td class="<?= $row['ranking_within_gender'] == 1 ? 'big-cell' : '' ?>"><?= htmlspecialchars($row['ranking_within_gender']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No male results found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script type="module" src="../bootstrap/js/bootstrap.bundle.min.js"></script>

</html>