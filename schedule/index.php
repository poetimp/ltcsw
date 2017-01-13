<?php
require_once 'include/general.php';
$error = false;
if (GET('FirstName')) {
    $Participant = ParticipantLookup(trim(GET('FirstName')), trim(GET('LastName')), GET('ChurchID'));
    if (!empty($Participant)) {
        redirect(sprintf('view.php?id=%s&church=%s', $Participant['ParticipantID'], $Participant['ChurchID']));
    } else {
        $error = sprintf('Could not find %s, %s', GET('LastName'), GET('FirstName'));
    }
}
$churches = FetchAll($ChurchesTable, null, '*', 'ChurchName');
$history = getParticipantsCookie();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LTC Participant Schedule</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <h1>Participant Schedule Lookup</h1>
            <?php if ($error) { ?>
                <div class="alert alert-warning"><?php echo $error ?></div>
            <?php } ?>
            <form method="get">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="FirstName" class="form-control" value="<?php echo GET('FirstName') ?>" required />
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="LastName" class="form-control" value="<?php echo GET('LastName') ?>" required />
                </div>

                <div class="form-group">
                    <label>Church</label>
                    <select name="ChurchID" class="form-control">
                        <option value="">-select-</option>
                        <?php
                        foreach ($churches as $church) {
                            $selected = (GET('ChurchID') == $church['ChurchID']) ? 'selected' : '';
                            echo sprintf('<option value="%s" %s>%s</option>', $church['ChurchID'], $selected, $church['ChurchName']);
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Lookup</button>
                </div>
            </form>
            <?php if (count($history) > 0) { ?>
                <h3>History</h3>
                <?php foreach ($history as $participant) { ?>
                    <?php $href = sprintf('view.php?id=%s&church=%s', $participant['ParticipantID'], $participant['ChurchID']); ?>
                    <p>
                        <a href="<?php echo $href ?>"><?php echo $participant['name'] ?></a>
                    </p>
                <?php } ?>
            <?php } ?>
        </div>
    </body>
</html>

