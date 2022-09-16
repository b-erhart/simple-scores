<?php
include('../modules/nfl.php');
?>
        <h1 style="text-align: center">NFL</h1>
        <p>Scores:</p>
<?php foreach ($matchdayData as &$match): ?>
<?php $dateTime = strtotime($match->MatchDateTime); ?>
        <p><?php echo($match->Team2->TeamName); ?> @ <?php echo($match->Team1->TeamName); ?></p>
<?php endforeach; ?>