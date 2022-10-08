<?php include(__DIR__ . '/../src/Bundesliga/BundesligaStandings.php'); ?>
            <nav class="local-nav">
                <div class="local-nav-items">
                    <a href="/bundesliga">Scores</a>
                    <a class="local-nav-active">Standings</a>
                </div>
            </nav>
        </header>
        <main>
            <h1>Bundesliga Standings</h1>
            <div class="card standings">
                <table>
                    <tr>
                        <th>#</th>
                        <th class="standings-stretch">Team</th>
                        <th><abbr title="Matches">M</abbr></th>
                        <th class="standings-optional"><abbr title="Wins-Draws-Losses">W-D-L</abbr></th>
                        <th><abbr title="Goals">G</abbr></th>
                        <th class="standings-optional"><abbr title="Net Goals">NG</abbr></th>
                        <th><abbr title="Points">Pts</abbr></th>
                    </tr>
<?php $i = 1; $rank = 1; $previousTablePoints = ''; $previousNetPoints = ''; foreach ($standings as $standing): ?>
                    <tr>
                        <td><?=($previousTablePoints != $standing->tablePoints || $previousNetPoints != $standing->netPoints) ? $rank = $i : $rank?>.</td>
                        <td><div class="team-badge" style="--team-color: <?=$standing->teamColor?>"><?=$standing->team?></div></td>
                        <td><?=$standing->matches?></td>
                        <td class="standings-optional"><?=$standing->wins?>-<?=$standing->ties?>-<?=$standing->losses?></td>
                        <td><?=$standing->pointsFor . ':' . $standing->pointsAgainst?></td>
                        <td class="standings-optional"><?=$standing->netPoints?></td>
                        <td><strong><?=$standing->tablePoints?></strong></td>
                    </tr>
<?php $i++; $previousTablePoints = $standing->tablePoints; $previousNetPoints = $standing->netPoints; endforeach; ?>
                </table>
            </div>
        </main>
