        <h1>NFL - <?=$currentMatchWeek->name?></h1>
<?php foreach($currentMatchWeek->matchDays as $matchDay): ?>
        <h4><?=$matchDay->date?></h4>
<?php foreach($matchDay->matches as $match): ?>
        <div class="scorecard">
            <p class="scorecard-time">
                <?=($match->isFinished) ? 'Final' : $match->time?>
            </p>
            <div class="scorecard-teams">
                <div class="scorecard-team-left">
                    <p><?=$match->awayTeamNameShort?></p>
                    <div class="scorecard-team-color" style="background: <?=$match->awayTeamColor?>"></div>
                    <!--<img src="<?=$match->Team2->TeamIconUrl?>"/>-->
                </div>
                <p class="scorecard-score">
                    <?=($match->isFinished) ? $match->awayTeamScore . ':' . $match->homeTeamScore : '@'?>
                </p>
                <div class="scorecard-team-right">
                    <p><?=$match->homeTeamNameShort?></p>
                    <div class="scorecard-team-color" style="background: <?=$match->homeTeamColor?>"></div>
                    <!--<img src="<?=$match->Team1->TeamIconUrl?>"/>-->
                </div>
            </div>
            <a class="scorecard-link" href="#">Details</a>
        </div>
<?php endforeach; ?>
<?php endforeach; ?>