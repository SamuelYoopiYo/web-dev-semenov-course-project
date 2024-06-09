async function getData() {
    try {
        const response = await fetch('../php/serverIndex.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'get_data'
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


async function main() {
    const postsData = await getData();
    const limitPosts = document.querySelector('[name="countPosts"]');
    let currentPage = 1;
    let rows = Number(limitPosts.value);


    const form = document.querySelector("form");
    const searchInput = document.querySelector('.form-control');

    form.addEventListener('input', (e) => {
        e.preventDefault();
        let searchedData = [];
        postsData.forEach(record => {
            let toCheck = record.Address.toLowerCase();
            if (toCheck.includes(searchInput.value.toLowerCase())) {
                searchedData.push(record);
            }
        });
        currentPage = 1; // Сбрасываем страницу на первую при поиске
        updatePageContent(searchedData);
    });

    limitPosts.addEventListener('change', (e) => {
        currentPage = 1; // Сбрасываем страницу на первую при изменении количества записей на странице
        updatePageContent(postsData);
    });

    const filterArea = document.querySelectorAll('.filterArea');
    const fieldsInObjectAndFilterArea = {
        coveringFilter: 'SurfaceTypeWinter',
        lockerRoomFilter: 'HasDressingRoom',
        foodFilter: 'HasEatery',
        toiletFilter: 'HasToilet',
        wifiFilter: 'HasWifi',
        lightFilter: 'Lighting',
        priceFilter: 'Paid',
        disabledFilter: 'DisabilityFriendly'
    };

    const filter = {};

    filterArea.forEach(area => {
        area.addEventListener('change', function () {
            if (this.value == "all") {
                delete filter[fieldsInObjectAndFilterArea[this.id]];
            } else {
                filter[fieldsInObjectAndFilterArea[this.id]] = this.value;
            }
            if (Object.keys(filter).length > 0) {
                console.log(filter);
                const result = postsData.filter(el => {
                    return Object.keys(filter).every(key => el[key] === filter[key]);
                });
                currentPage = 1; // Сбрасываем страницу на первую при применении фильтров
                updatePageContent(result);
            } else {
                currentPage = 1; // Сбрасываем страницу на первую, если фильтры неактивны
                updatePageContent(postsData);
            }
        });
    });

    async function init() {
        let myMap = new ymaps.Map("map", {
            center: [55.751574, 37.573856],
            zoom: 14
        });

        postsData.forEach(el => {
            let coord = el.geoData.split(',').map(Number);
            let correctedCoord = [coord[1], coord[0]];
            var placemark = new ymaps.Placemark(correctedCoord, {
                hintContent: el.NameWinter,
            });
            placemark.events.add('click', function () {
                const playgroundId = el.global_id;
                const url = '../php/card.php?id=' + playgroundId;
                window.open(url);
            });
            myMap.geoObjects.add(placemark);
        });
    }

    function displayList(arrData, rowPerPage, page) {
        const postsEl = document.querySelector('.posts');
        const tableHeaderName = ["№", "Название", "Адрес", "Подробнее"];
        postsEl.innerHTML = "";
        page--;

        let start = rowPerPage * page;
        let end = start + Number(rowPerPage);

        let paginatedData = arrData.slice(start, end);

        console.log(start, " ", end);

        const postsTable = document.createElement('table');
        postsTable.classList.add('table', 'table-Light', 'listPlayground', 'table-striped', 'text-center');
        const postsTableHeader = document.createElement('thead');
        const postsTableBody = document.createElement('tbody');
        const postsTableHeaderRow = document.createElement('tr');

        for (const headerName of tableHeaderName) {
            const postsHeaderCell = document.createElement('th');
            postsHeaderCell.textContent = headerName;
            if (headerName == 'Адрес') {
                postsHeaderCell.classList.add('computer');
            }
            postsTableHeaderRow.appendChild(postsHeaderCell);
        }

        postsTableHeader.appendChild(postsTableHeaderRow);
        postsTable.appendChild(postsTableHeader);
        let i = start + 1;
        paginatedData.forEach(el => {
            const row = document.createElement('tr');

            const numberPlayground = document.createElement('td');
            numberPlayground.textContent = i++;

            const namePlayground = document.createElement('td');
            namePlayground.textContent = el.ObjectName;

            const addressPlayground = document.createElement('td');
            addressPlayground.textContent = el.District + ' ' + el.Address;
            addressPlayground.classList.add('computer')

            const buttonMore = document.createElement('button');
            buttonMore.textContent = 'Подробнее';
            buttonMore.classList.add('btn', 'btn-primary');

            buttonMore.addEventListener('click', function () {
                const playgroundId = el.global_id;
                const url = '../php/card.php?id=' + playgroundId;
                window.open(url);
            });

            row.appendChild(numberPlayground);
            row.appendChild(namePlayground);
            row.appendChild(addressPlayground);

            const cellButton = document.createElement('td');
            cellButton.appendChild(buttonMore);
            row.appendChild(cellButton);

            postsTableBody.appendChild(row);
        });

        postsTable.appendChild(postsTableBody);
        postsEl.appendChild(postsTable);
    }
    function displayPagination(data, rowPerPage) {
        const paginationEl = document.querySelector('.pagination');
        paginationEl.innerHTML = '';

        const pagesCount = Math.ceil(data.length / rowPerPage);
        const ulEl = document.createElement("ul");
        ulEl.classList.add('pagination__list');

        // Создание кнопки "первая страница"
        const firstPageBtn = document.createElement("button");
        firstPageBtn.classList.add('btn', 'btn-primary');
        firstPageBtn.textContent = "<";
        firstPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updatePageContent(postsData);
            }
        });
        ulEl.appendChild(firstPageBtn);

        // Создание кнопки "последняя страница"
        const lastPageBtn = document.createElement("button");
        lastPageBtn.classList.add('btn', 'btn-primary');
        lastPageBtn.textContent = ">";


        lastPageBtn.addEventListener('click', () => {
            if (currentPage < pagesCount) {
                currentPage++;
                updatePageContent(postsData);
            }
        });

        for (let i = Math.max(0, currentPage - 5); i < Math.min(pagesCount, currentPage + 5); i++) {
            console.log('+');
            const liEl = displayPaginationBtn(i + 1);
            if (i === currentPage - 1) {
                liEl.classList.add('pagination__item--active');
            }
            liEl.addEventListener('click', () => {
                currentPage = i + 1;
                updatePageContent(postsData);
            });
            ulEl.appendChild(liEl);
        }


        ulEl.appendChild(lastPageBtn);
        paginationEl.appendChild(ulEl);
    }

    function displayPaginationBtn(page) {
        const liEl = document.createElement("li");
        liEl.classList.add('pagination__item');
        liEl.innerText = page;
        return liEl;
    }

    function updatePageContent(postsData) {
        console.log(rows, currentPage);
        rows = limitPosts.value;
        displayList(postsData, rows, currentPage);
        displayPagination(postsData, rows);
    }

    updatePageContent(postsData);
    ymaps.ready(init);
}

main();
