        <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option value="">Select match day...</option>
<?php foreach ($allMatchDays as $matchDay): ?>
<?php if ($matchDay->GroupName != ''): ?>
            <option value="./<?=$matchDay->GroupID?>"><?=$matchDay->GroupName?></option>
<?php endif; ?>
<?php endforeach; ?>
        </select>
        <h1 style="text-align: center">NFL</h1>
        <p><?=$currentMatchDay->GroupName?>:</p>
<?php foreach ($currentMatches as $match): ?>
<?php $dateTime = strtotime($match->MatchDateTime); ?>
        <p><?=$match->Team2->TeamName?> @ <?=$match->Team1->TeamName?></p>
<?php endforeach; ?>