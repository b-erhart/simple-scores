<?php include(__DIR__ . '/../src/Nfl/NflStandings.php'); ?>
            <nav class="local-nav">
                <div class="local-nav-items">
                    <a href="/nfl">Scores</a>
                    <a class="local-nav-active">Standings</a>
                </div>
            </nav>
        </header>
        <main>
            <h1>NFL Standings</h1>
            <div class="standings-grid">
<?php foreach ($divisions as $division => $entries): ?>
                <div class="card standings">
                    <h3 class="standings-title"><?=$division?></h3>
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Team</th>
                            <th><abbr title="Record">Rec</abbr></th>
                            <th><abbr title="Points">Pts</abbr></th>
                            <th><abbr title="Net Points">NP</abbr></th>
                        </tr>
<?php $i = 1; $rank = 1; $previousRecord = ''; foreach ($entries as $entry): ?>
                        <tr>
                            <td><?=($previousRecord != $entry->record) ? $rank = $i : $rank?><?=($entry->divisionRivalHasEqualRecord) ? '<a href="#tie-break-hint">*</a>' : ''?></td>
                            <td><div class="team-badge" style="--team-color: <?=$entry->teamColor?>"><?=$entry->team?></div></td>
                            <td><?=$entry->record?></td>
                            <td><?=$entry->pointsFor . '-' . $entry->pointsAgainst?></td>
                            <td><?=$entry->netPoints?></td>
                        </tr>
<?php $i++; $previousRecord = $entry->record; endforeach; ?>
                    </table>
                </div>
<?php endforeach; ?>
            </div>
            <p>Legend:<br/>
            <strong>REC</strong> - Record<br/>
            <strong>PTS</strong> - Points<br/>
            <strong>NP</strong> - Net Points</p>
            <p>* <a name="tie-break-hint" href="https://www.nfl.com/standings/tie-breaking-procedures" target="_blank">NFL tie breaking procedures↗</a> apply, but are not taken into account for this representation of the standings.</p>
        </main>
