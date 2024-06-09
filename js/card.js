async function getDataForId(id) {
    try {
        const response = await fetch('../php/serverIndex.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'get_data_for_id=' + encodeURIComponent(id)
        });

        console.log(response.status);

        if (!response.ok) {
            throw new Error('Ошибка при запросе данных');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

async function getDataForColumsName() {
    try {
        const response = await fetch('../php/serverIndex.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'get_column_name'
        });

        console.log(response.status);

        if (!response.ok) {
            throw new Error('Ошибка при запросе данных');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

const searchParams = new URLSearchParams(window.location.search);
const playgroundId = searchParams.get('id');

async function main() {
    const tableHeaderName = await getDataForColumsName();
    const tableCellsInfo = await getDataForId(playgroundId);
    console.log(tableCellsInfo, tableCellsInfo.geoData.coordinates)

    async function init() {//для карты
        
        let coord = tableCellsInfo.geoData.split(',').map(Number);
        console.log(coord);

        let correctedCoord = [coord[1], coord[0]];
        let myMap = new ymaps.Map("map", {
            center: correctedCoord,
            zoom: 14
        });

        var placemark = new ymaps.Placemark(correctedCoord, {
            hintContent: tableCellsInfo.NameWinter,
            balloonContent: tableCellsInfo.Address
        });

        myMap.geoObjects.add(placemark);
    }

    const mainInfo = document.querySelector(".mainInfo");

    const infoTable = document.createElement('table');
    infoTable.classList.add('table', 'table-Light', 'listPlayground', 'table-striped', 'text-center');
    const infoTableHeader = document.createElement('thead');
    const infoTableBody = document.createElement('tbody');
    const infoTableHeaderRow = document.createElement('tr');

    const infoHeaderСharacteristic = document.createElement('th');
    infoHeaderСharacteristic.textContent = 'Характеристика';
    infoTableHeaderRow.appendChild(infoHeaderСharacteristic);

    const infoHeaderAvailability = document.createElement('th');
    infoHeaderAvailability.textContent = 'Наличие';
    infoTableHeaderRow.appendChild(infoHeaderAvailability);
    console.log(tableHeaderName);

    for (const characteristic of tableHeaderName) {
            const row = document.createElement('tr');

            const cell1 = document.createElement('td');
            cell1.textContent = characteristic.ru_name;
            row.appendChild(cell1);

            const cell2 = document.createElement('td');
            if (tableCellsInfo[characteristic.en_name]) {
                cell2.textContent = tableCellsInfo[characteristic.en_name];
            } else {
                cell2.textContent = '-';
            }
            row.appendChild(cell2);

            infoTableBody.appendChild(row);
    }

    infoTable.appendChild(infoTableBody);
    mainInfo.appendChild(infoTable);
    ymaps.ready(init);
}

main();

async function addFavorite(id_user, playgroundId ) {
    try {
        const response = await fetch('../php/serverIndex.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id_grounds=' + playgroundId + '&id_user=' + id_user
        });

        console.log(response.status);

        if (!response.ok) {
            throw new Error('Ошибка при запросе данных');
        }
    } catch (error) {
        console.error(error);
    }
}
