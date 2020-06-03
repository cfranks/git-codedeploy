<?php
    foreach ($languageSections as $ml) {
        ?>
        <a href="<?= $controller->resolve_language_url($cID, $ml->getCollectionID()) ?>" title="<?= $languages[$ml->getCollectionID()] ?>" class="<?php if ($activeLanguage == $ml->getCollectionID()) { ?>selected<?php } ?>"><?= $languages[$ml->getCollectionID()] ?></a>
        <?php
    }
    ?>