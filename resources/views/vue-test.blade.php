<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
    <!-- <link rel="stylesheet" href="https://cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css"> -->
    @vite(['resources/css/app.css'])
    <title>Vueテスト</title>
</head>

<body>
    <div id="app">
        <vue-test-component></vue-test-component>
    </div>
    @vite(['resources/js/app.js'])
</body>

</html>