<?php include(__DIR__ . '/../src/Nfl/NflScores.php'); ?>
            <nav class="local-nav">
                <div class="local-nav-items">
                    <a <?=$showScores ? 'href="/nfl"' : 'class="local-nav-active"'?>>Schedule</a>
                    <a <?=$showScores ? 'class="local-nav-active"' : 'href="/nfl/scores"'?>>Scores</a>
                    <a href="/nfl/standings">Standings</a>
                </div>
            </nav>
        </header>
        <main>
<?php if (!isset($webServiceException)): ?>
            <select id="week" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
<?php foreach($allMatchweekMetas as $matchweekMeta): ?>
                <option <?=($selectedWeek == $matchweekMeta->getInSeasonId()) ? 'selected ' : ''?>value="<?='/nfl/' . $matchweekMeta->getInSeasonId()?>"><?=$matchweekMeta->getName()?><?=($matchweekMeta->getInSeasonId() == $currentMatchweekInSeasonId) ? ' (now)' : ''?></option>
<?php endforeach; ?>
            </select>
            <h1>NFL - <?=$matchweek->getName()?></h1>
<?php foreach($matchweek->getMatchdays() as $matchday): ?>
            <h4><?=$matchday->getDate()?></h4>
            <div class="score-grid">
<?php foreach($matchday->getMatchups() as $matchup): ?>
                <div class="card score">
                    <p class="score-time"><?=($matchup->isFinished) ? 'Final' : $matchup->time?></p>
                    <div class="score-teams">
                        <p class="team-badge score-team-left" style="--team-color: <?=$matchup->awayTeamColor?>"><?=$matchup->awayTeamNameShort?></p>
                        <p class="score-result"><?=$matchup->isFinished && $showScores ? $matchup->awayTeamScore . ':' . $matchup->homeTeamScore : '@'?></p>
                        <p class="team-badge score-team-right" style="--team-color: <?=$matchup->homeTeamColor?>"><?=$matchup->homeTeamNameShort?></p>
                    </div>
                    <a class="score-link" href="<?=$matchup->detailsLink?>" target="_blank">Details↗</a>
                </div>
<?php endforeach; ?>
            </div>
<?php endforeach; ?>
<?php else: ?>
            <p>Unable to retrieve web service data. Please try again later.</p>
            <p>Developer information: <code><?=$webServiceException->getMessage()?></code></p>
<?php endif; ?>
        </main>
