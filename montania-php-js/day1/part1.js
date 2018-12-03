var fs = require('fs');

fs.readFile('input', 'utf8', function (err, contents) {
    var commands = contents.split(/\n/);
    var numbers = commands.map(n => parseInt(n, 10));

    var result = numbers.reduce((value, current) => {
        return value + current
    }, 0);

    console.log('The resulting frequency is', result);
});