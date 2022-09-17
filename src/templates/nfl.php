        <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option value="">Select match day...</option>
<?php foreach ($allMatchDays as $matchDay): ?>
<?php if ($matchDay->GroupName != ''): ?>
            <option value="./<?=$matchDay->GroupID?>"><?=$matchDay->GroupName?></option>
<?php endif; ?>
<?php endforeach; ?>
        </select>
        <h1 class="title">NFL</h1>
        <h2 class="subtitle"><?=$currentMatchDay->GroupName?></h2>
<?php $previousTime = 0; ?>
<?php foreach ($currentMatches as $match): ?>
<?php $dateTime = strtotime($match->MatchDateTime); ?>
<?php if (date('l, j. F Y', $previousTime) != date('l, j. F Y', $dateTime)): ?>
        <p class="heading"><?=date('l, j. F Y', $dateTime);?></p>
<?php endif; ?>
        <div class="box level">
            <div class="level-left has-text-centered">
<?php if ($match->MatchIsFinished): ?>
                <p>Final</p>
<?php else: ?>
                <p><?=date('H:i', $dateTime);?> CEST</p>
<?php endif; ?>
            </div>
            <div class="level-item level has-text-centered">
                <div class="level-right">
                    <div>
                        <p>
                            <figure class="image is-32x32" style="margin: 0 8px;">
                                <img src="<?=$match->Team2->TeamIconUrl?>" style="border-radius: 5%;"/>
                            </figure>
                            <?=array_slice(explode(' ', trim($match->Team2->TeamName)), -1)[0];?>
                        </p>
                    </div>
                </div>
                <div>
                    <p>@</p>
                </div>
                <div class="level-left">
                    <div>
                        <figure class="image is-32x32" style="margin: 0 8px;">
                            <img src="<?=$match->Team1->TeamIconUrl?>" style="border-radius: 5%;"/>
                        </figure>
                        <p><?=array_slice(explode(' ', trim($match->Team1->TeamName)), -1)[0];?></p>
                    </div>
                </div>
            </div>
            <div class="level-right has-text-centered">
                <a href="#">Details</a>
            </div>
        </div>
<?php $previousTime = $dateTime; ?>
<?php endforeach; ?>