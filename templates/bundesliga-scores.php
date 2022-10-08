<?php include(__DIR__ . '/../src/Bundesliga/BundesligaScores.php'); ?>
            <nav class="local-nav">
                <div class="local-nav-items">
                    <a class="local-nav-active">Scores</a>
                    <a href="/bundesliga/standings">Standings</a>
                </div>
            </nav>
        </header>
        <main>
            <select id="week" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
<?php foreach($allMatchweekMetas as $matchweekMeta): ?>
                <option <?=($selectedWeek == $matchweekMeta->getInSeasonId()) ? 'selected ' : ''?>value="<?='/bundesliga/' . $matchweekMeta->getInSeasonId()?>"><?=$matchweekMeta->getName()?><?=($matchweekMeta->getInSeasonId() == $currentMatchweekInSeasonId) ? ' (now)' : ''?></option>
<?php endforeach; ?>
            </select>
            <h1>1. Bundesliga - <?=$matchweek->getName()?></h1>
<?php foreach($matchweek->getMatchdays() as $matchday): ?>
            <h4><?=$matchday->getDate()?></h4>
            <div class="score-grid">
<?php foreach($matchday->getMatchups() as $matchup): ?>
                <div class="card score">
                    <p class="score-time"><?=$matchup->isFinished ? 'Final' : ($matchup->isLive ? 'Live' : $matchup->time)?></p>
                    <div class="score-teams">
                        <p class="team-badge score-team-left" style="--team-color: <?=$matchup->homeTeamColor?>"><?=$matchup->homeTeamNameShort?></p>
                        <p class="score-result"><?=($matchup->isFinished || $matchup->isLive) ? $matchup->homeTeamScore . ':' . $matchup->awayTeamScore : 'vs.'?></p>
                        <p class="team-badge score-team-right" style="--team-color: <?=$matchup->awayTeamColor?>"><?=$matchup->awayTeamNameShort?></p>
                    </div>
                    <a class="score-link" href="<?=$matchup->detailsLink?>" target="_blank">Details↗</a>
                </div>
<?php endforeach; ?>
            </div>
<?php endforeach; ?>
        </main>

