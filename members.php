<?php
    $page_tittle = "User Authentication - Members";
    include_once "partials/headers.php";
    include_once "partials/parse_members.php";
?>
    <div class="container">
            <h1>Members List</h1><hr>
                <section class="col col-lg-12">
                    <?php if(count($members) > 0): ?>
                        <?php foreach($members as $member): ?>
                            <div class="row col-lg-4" style="margin-bottom: 10;">
                                <div class="media">
                                    <div class="media-left">
                                        <a href="public_profile.php?u=<?=$member['username']?>">
                                            <img src="<?= $member['avatar'] ?>" alt="Profile Pic" class="media-object" style="width: 60px;">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">Username:
                                            <a href="public_profile.php?u=<?=$member['username']?>"><?=$member['username']?></a>
                                        </h4>
                                        <p>Join Date<?=strftime("%b %d, %Y", strtotime($member['join_date'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No member found</p>
                    <?php endif; ?>
                </section>
    </div>
    
    <?php include_once "partials/footers.php"; ?>
</body>
</html>