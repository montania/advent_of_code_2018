let fs = require('fs');

fs.readFile('input', 'utf8', function (err, contents) {
    let commands = contents.split(/\n/);
    let numbers = commands.map(n => parseInt(n, 10));
    let visited = [];
    let frequency = 0;
    let index = 0;

    while (true) {

        if (typeof numbers[index] === 'undefined') {
            index = 0;
        }

        frequency += numbers[index];

        if (visited.indexOf(frequency) !== -1) {
            break;
        }

        visited.push(frequency);

        index++;
    }

    console.log('The first frequency visited twice is', frequency);
});