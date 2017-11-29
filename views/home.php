<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body>
    <main role="main">

      <?php foreach ($v['employees'] as $employee) : ?>
        <p><?= implode(',', $employee) ?></p>
      <?php endforeach; ?>

      <?php if (empty($v['employees'])) : ?>
        <p>No employees found</p>
      <?php endif; ?>

      <noscript>Sorting will not work without JavaScript.</noscript>
    </main>
  </body>
</html>
