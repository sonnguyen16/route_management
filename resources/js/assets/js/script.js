export function formatDate(date) {
    let day = new Date(date).toLocaleDateString();
    let time = new Date(date).toLocaleTimeString();
    time = time.split(":")[0] + ":" + time.split(":")[1];

    return day + " " + time;
}

export function numberToWords(number) {
    const units = ['', 'nghìn', 'triệu', 'tỷ', 'nghìn tỷ', 'triệu tỷ'];
    const words = ['', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];

    function convertGroupOfThree(num) {
        let result = '';
        const hundred = Math.floor(num / 100);
        const remainder = num % 100;

        if (hundred > 0) {
            result += words[hundred] + ' trăm';
            if (remainder > 0) {
                result += ' ';
            }
        }

        if (remainder > 0) {
            if (remainder < 10) {
                result += words[remainder];
            } else if (remainder < 20) {
                result += 'mười ' + words[remainder % 10];
            } else {
                result += words[Math.floor(remainder / 10)] + ' mươi';
                if (remainder % 10 > 0) {
                    result += ' ' + words[remainder % 10];
                }
            }
        }

        return result;
    }

    let result = '';
    let groupIndex = 0;

    while (number > 0) {
        const group = number % 1000;
        const groupWords = convertGroupOfThree(group);

        if (groupWords !== '') {
            result = groupWords + ' ' + units[groupIndex] + ' ' + result;
        }

        number = Math.floor(number / 1000);
        groupIndex++;
    }

    return result.trim();
}
