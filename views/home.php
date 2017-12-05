<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
    <main role="main">

      <h1>Borg Chart</h1>

      <?php if (!empty($v['employees'])) : ?>

        <table id="borg-employees" class="sortable">
          <thead>
            <tr>
              <th>Employee Name</th>
              <th>Boss Name</th>
              <th>Distance from CEO</th>
            </tr>
          </thead>

          <?php foreach ($v['employees'] as $employee) : ?>
            <tr>
              <td><?= $employee['name'] ?></td>
              <td><?= $employee['boss_name'] ?></td>
              <td><?= $employee['distance'] ?></td>
            </tr>
          <?php endforeach; ?>
        </table>

      <?php else : ?>
        <p>No employees found</p>
      <?php endif; ?>

      <noscript>Sorting will not work without JavaScript.</noscript>
    </main>

    <!--  NOTE: normally here I would bundle assets with NPM/Grunt,
          and serve it all minified together. CDNs will do for now. -->
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script src="/js/borg-chart.js"></script>
  </body>
</html>
