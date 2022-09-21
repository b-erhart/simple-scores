<!--        <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option value="">Select match day...</option>
<?php foreach ($allMatchDays as $matchDay): ?>
<?php if ($matchDay->GroupName != ''): ?>
            <option value="./<?=$matchDay->GroupID?>"><?=$matchDay->GroupName?></option>
<?php endif; ?>
<?php endforeach; ?>
        </select> -->
        <h1>NFL - <?=$currentMatchDay->GroupName?></h1>
<?php $previousTime = 0; ?>
<?php foreach ($currentMatches as $match): ?>
<?php $dateTime = strtotime($match->MatchDateTime); ?>
<?php if (date('l, j. F Y', $previousTime) != date('l, j. F Y', $dateTime)): ?>
        <h4><?=date('l, j. F Y', $dateTime);?></h4>
<?php endif; ?>
        <div class="scorecard">
            <p class="scorecard-time">
                <?=($match->MatchIsFinished) ? 'Final' : date('H:i', $dateTime) . ' CEST'?>
            </p>
            <div class="scorecard-teams">
                <div class="scorecard-team-left">
                    <p><?=array_slice(explode(' ', trim($match->Team2->TeamName)), -1)[0];?></p>
                    <img src="<?=$match->Team2->TeamIconUrl?>"/>
                </div>
                <p class="scorecard-score">
                    <?=($match->MatchIsFinished) ? $match->MatchResults[0]->PointsTeam2 . ':' . $match->MatchResults[0]->PointsTeam1 : '@'?>
                </p>
                <div class="scorecard-team-right">
                    <p><?=array_slice(explode(' ', trim($match->Team1->TeamName)), -1)[0];?></p>
                    <img src="<?=$match->Team1->TeamIconUrl?>"/>
                </div>
            </div>
            <a class="scorecard-link" href="#">Details</a>
        </div>
<?php $previousTime = $dateTime; ?>
<?php endforeach; ?>