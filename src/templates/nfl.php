        <div class="local-nav">
            <a class="local-nav-active">Scores</a>
            <a href="/nfl/standings">Standings</a>
        </div>
        <select id="week" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
<?php foreach($allMatchDaysMeta as $matchDayMeta): ?>
            <option <?=($weekId == $matchDayMeta->id) ? 'selected ' : ''?>value="<?=$matchDayMeta->link?>"><?=$matchDayMeta->name?></option>
<?php endforeach; ?>
        </select>
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
            <a class="scorecard-link" href="<?=$match->detailsLink?>" target="_blank">Details↗</a>
        </div>
<?php endforeach; ?>
<?php endforeach; ?>