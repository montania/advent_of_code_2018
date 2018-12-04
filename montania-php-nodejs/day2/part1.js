var fs = require('fs');

fs.readFile('input', 'utf8', function (err, contents) {
    var commands = contents.split(/\n/);

    // Filter and get length of box ids with the same character twice.
    var twice = commands.filter(id => {
        for (let index=0; index < id.length; index++) {
            if (id.split(id[index]).length-1 === 2) {
                return true
            }
        }

        return false;
    }).length;

    // Do the same with ids that contains the same character tree times.
    var threeTimes = commands.filter(id => {
        for (let index=0; index < id.length; index++) {
            if (id.split(id[index]).length-1 === 3) {
                return true
            }
        }

        return false;
    }).length;

    // Calculate the resulting checksum by multiline the result from twice and threeTimes filters.
    console.log('The resulting checksum is', twice * threeTimes);
});