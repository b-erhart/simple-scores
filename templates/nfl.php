            <nav class="local-nav">
                <div class="local-nav-items">
                    <a class="local-nav-active">Scores</a>
                    <a href="/nfl/standings">Standings</a>
                </div>
            </nav>
        </header>
        <main>
            <select id="week" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
<?php foreach($allMatchDaysMeta as $matchDayMeta): ?>
                <option <?=($weekId == $matchDayMeta->id) ? 'selected ' : ''?>value="<?=$matchDayMeta->link?>"><?=$matchDayMeta->name?></option>
<?php endforeach; ?>
            </select>
            <h1>NFL - <?=$currentMatchWeek->name?></h1>
<?php foreach($currentMatchWeek->matchDays as $matchDay): ?>
            <h4><?=$matchDay->date?></h4>
            <div class="score-grid">
<?php foreach($matchDay->matches as $match): ?>
                <div class="card score">
                    <p class="score-time"><?=($match->isFinished) ? 'Final' : $match->time?></p>
                    <div class="score-teams">
                        <p class="team-badge score-team-left" style="--team-color: <?=$match->awayTeamColor?>"><?=$match->awayTeamNameShort?></p>
                        <p class="score-result"><?=($match->isFinished) ? $match->awayTeamScore . ':' . $match->homeTeamScore : '@'?></p>
                        <p class="team-badge score-team-right" style="--team-color: <?=$match->homeTeamColor?>"><?=$match->homeTeamNameShort?></p>
                    </div>
                    <a class="score-link" href="<?=$match->detailsLink?>" target="_blank">Details↗</a>
                </div>
<?php endforeach; ?>
            </div>
<?php endforeach; ?>
        </main>
