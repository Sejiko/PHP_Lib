<?php
require '_handler.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
		<!--scripts-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!--end scripts-->

		<meta name = "author" content = "p.pastore"/>

        <title>PHP_Library</title>
    </head>
    <body>
        <main>	
			<div id="statisticOutput">
				<h1 id="tableName">PHP_Library</h1>
				<?php
				echo createHtmlTable($stats);
				echo generateTabelForModules();
				?>
			</div>
        </main>
    </body>
</html>
