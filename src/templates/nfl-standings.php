        <div class="local-nav">
            <a href="/nfl">Scores</a>
            <a class="local-nav-active">Standings</a>
        </div>
        <h1>NFL Standings</h1>
        <div class="standings-grid">
<?php foreach ($divisions as $division): ?>
            <div class="standingscard">
                <h3 class="standingscard-title"><?=$division->name?></h3>
                <table>
                    <tr>
                        <th></th>
                        <th>Team</th>
                        <th><abbr title="Record">Rec</abbr></th>
                        <th><abbr title="Points">Pts</abbr></th>
                        <th><abbr title="Net Points">NP</abbr></th>
                    </tr>
<?php $i = 1; $rank = 1; $previousRecord = ''; foreach ($division->entries as $entry): ?>
                    <tr>
                        <td><?=($previousRecord != $entry->record) ? $rank = $i : $rank?><?=($entry->divisionRivalHasEqualRecord) ? '<a href="#tie-break-hint">*</a>' : ''?></td>
                        <td><div class="standingscard-team" style="background: <?=$entry->teamColor?>"><?=$entry->team?></div></td>
                        <!--<td class="standingscard-icon-column">
                            <div class="standingscard-team-color" style="background: <?=$entry->teamColor?>"></div>
                            <?=$entry->team?>

                        </td>-->
                        <td><?=$entry->record?></td>
                        <td><?=$entry->points?></td>
                        <td><?=$entry->netPoints?></td>
                    </tr>
<?php $i++; $previousRecord = $entry->record; endforeach; ?>
                </table>
            </div>
<?php endforeach; ?>
        </div>
        <p>* <a name="tie-break-hint" href="https://www.nfl.com/standings/tie-breaking-procedures">NFL tie breaking procedures↗</a> apply, but are not taken into account for this representation of the standings.</p>