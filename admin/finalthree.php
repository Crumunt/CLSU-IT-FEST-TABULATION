<?php
$host = 'localhost';
$dbname = 'tabulation';
$username = 'root';
$password = '';
$port = '3306';     

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

$query = "
    SELECT 
        gender, 
        participant_id, 
        participant_name, 
        ranking_within_gender,
        college,
        avg_total_score,
        participant_num,
        DENSE_RANK() OVER (PARTITION BY gender ORDER BY ranking_within_gender) AS rank
    FROM top_3_participants
    WHERE gender IN ('Male', 'Female')
    ORDER BY 
        gender, 
        ranking_within_gender; 
";

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
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Top Three Candidates for Both Male and Female</h2>

        <!-- Female Participants Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">Top Female Participants</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="tableHead text-center">
                        <tr>
                            <th>Participant #</th>
                            <th>Element</th>
                            <th>Participant Name</th>
                            <!-- <th>Total Score</th> -->
                            <th>Average Ranking</th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody text-center">
                        <?php if (!empty($femaleResults)): ?>
                            <?php foreach ($femaleResults as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['participant_num']) ?></td>
                                    <td><?= htmlspecialchars($row['college']) ?></td>
                                    <td><?= htmlspecialchars($row['participant_name']) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['avg_total_score'], 2)) ?></td>
                                    <!-- <td><?= htmlspecialchars($row['avg_gender_ranking']) ?></td> -->
                                    <td><?= htmlspecialchars($row['rank']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No female results found.</td>
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
                            <th>Participant #</th>
                            <th>Element</th>
                            <th>Participant </th>
                            <!-- <th>Total Score</th> -->
                            <th>Average Total Score</th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody text-center">
                        <?php if (!empty($maleResults)): ?>
                            <?php foreach ($maleResults as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['participant_num']) ?></td>
                                    <td><?= htmlspecialchars($row['college']) ?></td>
                                    <td><?= htmlspecialchars($row['participant_name']) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['avg_total_score'], 2)) ?></td>
                                    <!-- <td><?= htmlspecialchars($row['avg_gender_ranking']) ?></td> -->
                                    <td><?= htmlspecialchars($row['rank']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No male results found.</td>
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