<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-logo {
            display: table-cell;
            width: 30%;
            text-align: center;
        }

        .header-logo img {
            max-height: 180px;
        }

        .header-details {
            display: table-cell;
            width: 33%;
            vertical-align: middle;
            text-align: center;
        }

        .header-details .hospital_name {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            font-family: "Times New Roman", serif;
        }

        .header-details p {
            margin: 5px 0 0 0;
            font-size: 14px;
            line-height: 1.5;
        }

        .header-qr {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: middle;
        }

        .header-patient {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: middle;
        }

        .header-patient h4 {
            margin: 0;
            font-size: 16px;
            font-weight: normal;
        }

        .header-patient p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }

        .prescription-title {
            font-size: 32px;
            text-decoration: underline;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            font-family: "Times New Roman" , serif;
        }

        .medicines-table {
            width: 90%;
            border-collapse: collapse;
            /*margin-bottom: 20px;*/
            margin: 0 auto;
        }

        .medicines-table th, .medicines-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            font-family: "Times New Roman" , 'Arial' , serif;
        }

        .medicines-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;

        }

        .medicines-table td:first-child {
            width: 10%;
        }

        .medicines-table td:nth-child(2) {
            width: 60%;
            font-size: 1.2em;
        }

        .medicines-table td:nth-child(3) {
            width: 30%;
        }

    </style>
</head>
<body>
<div class="header">

    <div class="header-details">
        <p class="hospital_name">{{ config('app.hospital.name') }}</p>
        <p>{{ config('app.hospital.address') }}</p>
        <p>{{ config('app.hospital.phone') }}</p>
    </div>
    <div class="header-logo">
        <img
            src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEBLAEsAAD/4QBvRXhpZgAASUkqAAgAAAABAA4BAgBNAAAAGgAAAAAAAABQaGFybWFjeSBzbmFrZSBzeW1ib2wuIFNpbXBsZSBmbGF0IGlsbHVzdHJhdGlvbiBpc29sYXRlZCBvbiB3aGl0ZSBiYWNrZ3JvdW5kLv/hBWNodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iPgoJPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KCQk8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtbG5zOklwdGM0eG1wQ29yZT0iaHR0cDovL2lwdGMub3JnL3N0ZC9JcHRjNHhtcENvcmUvMS4wL3htbG5zLyIgICB4bWxuczpHZXR0eUltYWdlc0dJRlQ9Imh0dHA6Ly94bXAuZ2V0dHlpbWFnZXMuY29tL2dpZnQvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpwbHVzPSJodHRwOi8vbnMudXNlcGx1cy5vcmcvbGRmL3htcC8xLjAvIiAgeG1sbnM6aXB0Y0V4dD0iaHR0cDovL2lwdGMub3JnL3N0ZC9JcHRjNHhtcEV4dC8yMDA4LTAyLTI5LyIgeG1sbnM6eG1wUmlnaHRzPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvcmlnaHRzLyIgcGhvdG9zaG9wOkNyZWRpdD0iR2V0dHkgSW1hZ2VzIiBHZXR0eUltYWdlc0dJRlQ6QXNzZXRJRD0iMTIxNTk3NzgwMyIgeG1wUmlnaHRzOldlYlN0YXRlbWVudD0iaHR0cHM6Ly93d3cuaXN0b2NrcGhvdG8uY29tL2xlZ2FsL2xpY2Vuc2UtYWdyZWVtZW50P3V0bV9tZWRpdW09b3JnYW5pYyZhbXA7dXRtX3NvdXJjZT1nb29nbGUmYW1wO3V0bV9jYW1wYWlnbj1pcHRjdXJsIiA+CjxkYzpjcmVhdG9yPjxyZGY6U2VxPjxyZGY6bGk+RGltaXRyaXM2NjwvcmRmOmxpPjwvcmRmOlNlcT48L2RjOmNyZWF0b3I+PGRjOmRlc2NyaXB0aW9uPjxyZGY6QWx0PjxyZGY6bGkgeG1sOmxhbmc9IngtZGVmYXVsdCI+UGhhcm1hY3kgc25ha2Ugc3ltYm9sLiBTaW1wbGUgZmxhdCBpbGx1c3RyYXRpb24gaXNvbGF0ZWQgb24gd2hpdGUgYmFja2dyb3VuZC48L3JkZjpsaT48L3JkZjpBbHQ+PC9kYzpkZXNjcmlwdGlvbj4KPHBsdXM6TGljZW5zb3I+PHJkZjpTZXE+PHJkZjpsaSByZGY6cGFyc2VUeXBlPSdSZXNvdXJjZSc+PHBsdXM6TGljZW5zb3JVUkw+aHR0cHM6Ly93d3cuaXN0b2NrcGhvdG8uY29tL3Bob3RvL2xpY2Vuc2UtZ20xMjE1OTc3ODAzLT91dG1fbWVkaXVtPW9yZ2FuaWMmYW1wO3V0bV9zb3VyY2U9Z29vZ2xlJmFtcDt1dG1fY2FtcGFpZ249aXB0Y3VybDwvcGx1czpMaWNlbnNvclVSTD48L3JkZjpsaT48L3JkZjpTZXE+PC9wbHVzOkxpY2Vuc29yPgoJCTwvcmRmOkRlc2NyaXB0aW9uPgoJPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KPD94cGFja2V0IGVuZD0idyI/Pgr/7QCOUGhvdG9zaG9wIDMuMAA4QklNBAQAAAAAAHIcAlAACkRpbWl0cmlzNjYcAngATVBoYXJtYWN5IHNuYWtlIHN5bWJvbC4gU2ltcGxlIGZsYXQgaWxsdXN0cmF0aW9uIGlzb2xhdGVkIG9uIHdoaXRlIGJhY2tncm91bmQuHAJuAAxHZXR0eSBJbWFnZXP/2wBDAAoHBwgHBgoICAgLCgoLDhgQDg0NDh0VFhEYIx8lJCIfIiEmKzcvJik0KSEiMEExNDk7Pj4+JS5ESUM8SDc9Pjv/wgALCAJkAmQBAREA/8QAGwABAAIDAQEAAAAAAAAAAAAAAAUGAwQHAQL/2gAIAQEAAAABuYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB8/P36AAAAAAAAAAAHlaq0V85ZWxWbIAAAAAAAAAABr86hm396fy3uhyoAAAAAAAAAAY+Yxdkum8x1+maGXp0mAAAAAAAAAAKNUrZefQxc6g5LqfoAAAAAAAAADW5Ls9X+wMHKNfpU4AAAAAAAAAAqtDuV0AKJVLdeAAAAAAAAAAHOa/0mdAKrQ7F0QAAAAAAAAADzkWv1OTAKnRbH0MAAAAAAAAACP5S6fLgFArFwuwAAAAAAAAAFHqK3XgBj5Lr9KnAAAAAAAAAARPLx1GVAp1K3OsfQAAAAAAAAAKzz8X60AieZfF+tAAAAAAAAAAEBzgdEsQRvM9eb6V6AAAAAAAAABrcj8Ot7Ygee4d7p+yAAAAAAAAAAc4gE90gxUupN/pe4AAAAAAAAAANHmLp295XqPppvoewAAAAAAAAAADG+4GmxTJdLb6AAAAAAAAAAAxViOMts3AAAAAAAAARFQAAAABtXv0AAAAAAB88rjwAAAAv1oAAAAAAAEPzLwAAAAmemegAAAAAABTqUAAAA2epbYAAAAAAAPKDWAAAAZelywAAAAAAAD5odXAAAGfpEuAAAAAAAAPKlSfkAACS6LvgAAAAAAAAgfmnRQAA+7dccgAAAAAAAAPOUavRcVRiQAZbLb90AAAAAAAABj466dMI2vw0Zrj63pacn8oAAAAAAAAA+eP471bA8ieYJrpX0AAAAAAAAAAUCsfd4tP0Izlic6UAAAAAAAAAANfmUc3LHOymbSoWtIdDAAAAAAAAAADDR6x4e7m9t7W5JSmQAAAAAAAAAANKtQMX8gZJ+2ywAAAAAAAAAAMcfoauDDpRWqWu8/QAAAAAAAAAAEdzrPO3GNq9W+bXewAAAAAAAAAAIzlic6URnM8HV98AAAAAAAAAAEZyxOdKFFqfQLMAAAAAAAAAACM5YnOlHzzKI6TOgAAAAAAAAAAjOWJzpXkNS4eS6l9AAAAAAAAAABhjuYJyYqeuluibgAAAAAAAAAAh6bCX2gpzNFS9jnvQAAAAAAAAABXeeZ7JPc1TnSPoAAAAAAAAAAByrN0nNGcsTnSgAAAAAAAAAADk8t0JGcsTnSgAAAAAAAAAACoUi3XLU5X5OdKAAAAAAAAAAAPKnTPLBKNuxAAAAAAAAAAABhr8HFaKSnLLJAAAAAAAAAAADTqu3mQ8FI3mQAAAAAAAAAAARnLE50p5C1S0zoAAAAAAAAAAEZyxOdKHkBLbQAAAAAAAAAA8jYKkpG7y2wPn6AAAAAAAAAAalSrWsD2Xtdi9AAAAAAAAAAeVOlYwAlehb4AAAAAAAAAPKJVQAGfpcoAAAAAAAAAGhVQABuW0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD/xAAvEAACAgIAAwcEAQQDAAAAAAADBAIFAAEQFWAREhMWIDVQFDAxQAYjJDSQISI2/9oACAEBAAEFAv8AZb261nfj0TvfZpq6CLDWjZs3KUuESkhgrZwWL3gp5CcSR6DMYa4nrIrm+EVGJ5tJrWSjKO+CzhlJpPjdh0DOcRwfdm6bEacjGgJrr64THAmmKVcuNJGUlgizCRFyLgOgLtzvTyortE9cxxLCyrtpzxBrajX5188wbQF5y3Oai+2mYx1CPrMKJxHDJc+VB/GR+evjd1fKAP2r4XdPlAT+p89dF77+U8O7XfZv9f2uUe/7/wCeYJ4rGV3t/wBm/l/Qyi12vfOvF8FLhVy71d9m9L3ms/j4/wDt87cPwLrhTPwFr7E56HA5dnPlQHwUPnLM+wI+itPthH13bnZHFAbZZ1rUdfOX3+F6KH/E9T7sUwzlKc8pU/CD87dQ71f6KSHdQ9Lro0xnORkuViP1Zvx88wLxl967N8PziovAW9D9oNTRTTOTFFCOGACC4vn7hXwG+FQr47fGZICg9dbnn54KJkcKssNQXQDasGwMLFWIuuVkiisFAcG7gIMZbM1LgjWEb2EA1x9BThAmoQgPWNW64MasWGuMBzLNKl1HNa7NdCE8TusoWDWcgYzkDGcgYwdBPvrqhVj8tZFOBbnbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cznbmc7cxazeZY+NlrUovK7UZ/dpU/CF8dZJfWL71vW/26xL6s/wCPkLiv7f21wTZMsvBUHyNrW+Bv9gQ5mIgjFIXyW9durKq2H9cISMEQr4JQ+VsKfU8lHcJfppomckomJMfyz9npIsxJ2wm646m/0IwlOSdJkYRhH5d03juDJMU6xsjgWqYB8YrGlvujHMslqMk8XUCrH5gm+wfCqjqNdwOgsxhaDWEqHB5Ncw/TqO5ZBBsmComJYGkWHkBwFH5reu3RIbGXKNvUheqyhD6DhUR1Kx1rWugLtXwz5GUoSSuoE1repa9Fj7fwp/cugDBgcTqREy8ANnW2G/lrBWyZMgUZODYpHUlTO6yVa5HKoBR2PQJRQMNylIPN63HfHt7Mg6yPI3DscjfM6yP8glkL4G8HapkyM4z10CdQDOj0GFrHBZuO479cCTHte7ZFitku10HKEZ6nWpzyVGpvN0As3/H83QFydK5HCKnD6ELiUN63qWug35bijp9vWatXdZXWjDDWGr1T4zREhkoyhLKd/cJ9B2Pt/Cn9y4uIicgYMwF/G0j/AFKnQVj7fwp/cvRfw1o2UW+1LoKx9v4U/uXHe9R1Zt6baynF4df0FY+38Kf3LPxjFqqDHLMznBFSTjGtajHoGZRjx6cSVnCn9ytbBhdkjBjcU64ze1lhqi6AcsgKYxbtHze9y3r/AM7wp/crv/mwGmyXBUbM8Xp1g5+OgbWw+lhve97CuViQqEssZDpen4U/uXZrt6EsC7K9WpfWnGOAYZY+38Kf3LoV2Gxu0jEQs8LH2/hT+5dC3SO56xG67mhlGaNhrckN63reU/uXQz1LomyiIGQykDIN4xDNXCZtdlKfFK5UTHQ5BDLE1GvPC0bUMmi0PN63rNb3rBWLYcDfyxewWZ6HbLICsP5BLOdqzwe6p2ZKNWWFoTRwyjC+K2zC+Kuhbj0JY+38Kf3LixVKsYaubQnXWMXI9B2Pt/Cn9y9LVbqU1y+MLoD8YSxUFjtusVbggxFVuFynPBnCb09mu3547QVosXs94Vkx9+rW+zAWrYMWugGz89AP3Oh5Mkyz+2nYmU2q4JwfztpabLv7wTEXIi7B0XzbginB5fJnl8meXyZ5fJnl8meXyZ5fJnl8meXyZ5fJnl8meXyZ5fJnl8meXyZ5fJitQdU/+23/xAA6EAABAgIFCAgGAgIDAAAAAAABAAIDERASISIxM0FRUmBhcZIEEyAjMlCBkRQwQmJyoUCCkME0g6L/2gAIAQEABj8C/wAltpXiHvsTMqrBHWu05llao0NsV5xPGi5Ec3gVlK40OUo7erOnEKsxwcDnGwhiRHSaFVFyFq03YEQ/1VvR4nKpOBHGmcN1mduYqy68Yt2CL3mTRip4MHhbQIkbu4f7K7uGBvz0ye0OG8KcLunbsFKI2zM4YUCJDMnBV8HDxDYH4VhsFrqPiYwu/SNPbLHtDmnMVWZbCdhuoD/pNjuCn5++KfpCL3WkmZTIWnHgg1okBh8h0N4scnQnYtNDZ4su+fshD6zRFjf1HyocUfUJGiLD0ifn9XUEqGfcSflQz99H9D5/EiaziaIP4/KhN0unQToYfPor/tphcJfKbD1G0RonAeffDQjORvGn4aIZTN0/JL3YNEynxT9RobPF97z17hibB2WOd4hYfkfCsNptfQyEM5t4IAYDz1v59l/59ueMQ+EIvcZk40de8XomHDz4nVcD2Z6zie1Wda4+FulGJEMyaJuHdMx37vP4kPWapHsQ4Wq3s1G34ujQjEiOrONFRmGc6EIUMSA2ArgXIlvrSHkXIdvYL3uDWjOUYfRro189NVgszu0Lq4Y4nTsCYTvQ6FUitl/tVITZ/wCkIbfU6aasLvX7sFWivnuzCms65C06UIcNsgNg5PaHDeFJjQ0bhRJh6132qTnVWaraajGlzjmCETpV46ikNhe7Da33LvOksq6onJZWH+1lYf7WVh/td7GbV+1VYTJb858362BK74pjMsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKsWcqxZyrFnKmwmlt46vlxaRMFOh/Ti07v53xDxefhw8vkMo21qkbCP5l7Js8XmJ6VCFv1j/f8sQoYtP6QhMzZ9PmRjwR3ZxGr/JEOGJuKli8+J3mcijGgCcPO3V/jiHDbNxWtEOLvNjF6NY7OzSqrhIjMf4lwSbncVVhi3O7T5uxlWvO124Ks039OcKbhWZrD+DVY0uJzBB/SuQINYAAMw84iRN9iD4bi1wzhO61gu2VtKrQ+6duwVrKzdZtvzasNhcdwU+kOqDQMVKEwDfn85cd1MKWe2m/CE9IsK7mN6OWTr/iVfhPbxHZsBKuwH+oku8cxn7U31oh34KrDYGjcPO5JzDi0yo+GcbzbW7x24xqidXRSwOAIkcVYJbAde0XYmPGgOaZEYFBnSbjtbMVMGY7Mb8aWcDsC6HEE2lVXWtPhdpp7qIRuzKUeFPe1ZSofuCuPa7gaIkNuLgsmDwcreju9EyvCe2w4jYIsiNrNKL+j326udSIkexYrsd/usoDxarWQz6K90cejlfhPb+1lqv5WKbXBw3bBd7DB351OBF9HK2CXDS21ScCDv8AkTY8tO4qUSUUb8VJrqr9V2wcntDuIVsBvpYrK7eBV2O4eis6T/5V2Mw+isa1/By7yE9vp2BD6SazdfQpgzB2EiuaZENxC/5D/dZf3ATYMSqQc8qL8IT0tsVbo7q41TiqrgQRmNA6NEN13h3bCRvxpZwPYk4SfmcnQni81TChxc5Fuwcb8aWcD2YT85FtDhofsHG/GlnA9iZMgFNvgbY2hs/rvbBxvxpZwNPjru0NVXwQ9UUBn0jxFBowGwV97W8SormGbS3EUs4FdVCfVFWeC7yK53E0zAqs1iurhjidOwMjffqhWO6tuhqmTMr/AK6WcCv6BXIDz6LvC2GPdTcOtd92wXVw8o79BTNpVWEwuU4sUN3C1RIQMw1lLOBU5bCxXfdJSd4G2uQZDaGtGYURvxpZwOw0Zp1ijDdZ1mHGmN+NLOB2G+Jhi0eMUCH0m0a6rQ3hw3KMAJmqpGhnA7DmJ0aTTqZlViMLTvVaG8tO5SiBsT9FSjwD6ia+lvu1CPAjVpZpz2IqxGBw3qcNzoZ9wrha/wBZK9Af7K0SVhkrsZx3OtUo8Ke9quRLdU2HYeJFbKbRO1X4APArvILvYFVGw21jmqyVwvZ6ruojX8bF3kJzd6k49YzQVOG63O04jYWN+NLOB7E6nVu0tXWwjWA+pqqvsijEadhI340s4HtfEdF7uM23cUHFtV2Dm6DsDar0dvpaokJlclwlOVLYrwSBoVry3iF3cRruB7M/P60V4apdHZVGs5d7Fc717diylcaH2qrF7p2/BTGwBh9GvOzvzBVnuLic5+ZYazNUqtDNuducefGBANz6nafniJDdJwUxY8eJvnhhwogZWxKy7fZZdvssu32WXb7LLt9ll2+yy7fZZdvssu32WXb7LLt9ll2+yy7fZZdvssu32WXb7IRWdIbZiJY/5bv/xAAsEAEAAQIDBQgDAQEAAAAAAAABEQAxIUFREGBhcYEgUJGhscHw8TDR4UCQ/9oACAEBAAE/If8Apa2Ac6Fs+5IEQAutOsI1Qf3S7K+S1p2UdVOx+eZJTh0jPnenxuhoC2hJJuIYbzlKUcgN+ey9FyRqKj58WqRF0EbcIfMUc+V3OXDcI3hZTUoz0dx57EAUxD4RQoe1k+LbwDZNRzJ1Hgq5l1C2IJbIlHhGC03cFw0G5uRsjZJcfNr28KEDRiSdi1aOydWU0EAMjZ7/ALCrvNypdJZONEWzxaZqOoOAZH4JaxQ1lCrnx2IZS8vby7/k1jK8j+7Liaeo+34jBeuH3siy4np99/zRcD67++ybZo+Me34gc6Q8nZEjX2O/nAmuHgeOwxy/4os6vgP7s+KCTv7GaEQc3DaT9F4H8UfuGNzfhs5BB9f139h0ZzthltYQk7Wly/C1kMSr/LvLZOxCq9vLv1H4xrn2XWnE+J+Aw0AZGRsstYzTNRlQEB36np/R7LY8j2nbf4MD7/KlVLlOeyaiFfl/ff0HfIPfs4z+Ye3al7km/wDFeWfQaGx7+JdAAAIDv4ncwOdIwQjCbQUAStaqAPPPsoIfA81NCzDsIFAxyxUHh+PHcBEyz4Zja2cT4uR2LwIErEjZVdy0pVKsrnswphesKO4DMutXcHCdb6zWkbEWcuShbE3cuJrGfb6zYsEtT8XEYOtScaDh0NpabMd+SiJee3D4J+mrgn6KlAlwKl4jksDrUv0ZH92kLIAmsPF0FjnrQAABYNxcTMySQ8qWQPAvRXw/0r4f6V8P9KioGaLL41CVr9Q97yTksEn+8xjGMYxjGMYxjGMYxjGMYxjGMZLM4jkM3u6FEIRzpeTLmP8Acm8B35f33eCiXX2pGaiEcv8AYpozlrwoAAEB3hlzWc/9aXRmZDWjFwu1Ne8b0uKNh5v1/pUS2AKtV9c5d5gwCOCOdOpXCv8Ax/nUl5Ssih/kcO9oUlzI5KSJdCEJ/khcDwR+6uSL1+9wTmwE0QaALcOeU+0Pb66f4SVgQStOyoMm9WsJqwEB3w01Is5DAqyhBKfsYwLdNSWK6MXSphcuC/LwcPNUcTn38VxJ25de+UEuN26bCuc7ZZm6x0rHB9yrcBrNXmSJ2Woc4E156oHnWJi85VHLxSPAVw4NF33IKyRV5YrpsSwEe2CsDsDdtsSQCcqs5yG4Cj6SbDt3yGVQRWfeaUZMlkb/AIP+U03BiaGGoM5It/W2WH9S6VEB42PKvJ5F/KAkzhOyIfCk2qw8mV+yL0pvJuOZbhDnuBpdLrrf3TlwXEt2BKUjwqxfhNq9cqV6l496zI6HtXkhRTAwLQNON8FO4Ucf6B1oGYPD9yp2gLhQgR+DifaKoDwS8VRfTE9Ndw48bSSrOvllXy89ad5kGl/Z/dH80RXy6udWpNXB47RRkwadNZM3NrRkwJEz3EbzhiQlW/rzrPzzXtUXNOCDgbJ+GuMoh6Gf6p1fsEJsx09jc2m4/wDymnYw2Iw7n8qJkkNCgkJZqRMhzGDuN/ymnZKl2dH3sdSyx4G43/KadhyYEq5UqtvO47EaIU/p6bjf8ppsUErBxodPFfnappepc9dhgExtIowYCA03CPk3hFFdZiw7flNKiohWFrw+8DaP1GWHTWj8IzLrV3BlW+PjpSSdOXxqeJM1mvWeu35TSgwAuh1rxQsJUY8WMvKnxwZ2eFACAgMjcFjfDv51IkUxVzqaPzgwOtGHBbKlTNEueO35TSsgTruKzmyPIwqRZDwM+FW4IB2f+U03G1yLxxpTYEE6Ldr/AJTTcYlMGA01q1JIywBc560Rd81NLGTABK1AhEydnymm48gZxVdy0pWb5CuOFnFQ43V9iocnJNZBdoRVA2RibkcBDiandEqMUL4SedWv8SR5UrCLiUjKLUqGhOQ86JARqx5NQQY9x0ElRxZQfHI6Dx14KJcVh+VXVeEjzqaRtMSkMINGHjTpxm4nJq183IB/ymm1BISSpwSOH5UQZiy+cysMx/2Dcf8A5TTtGUHQjCl3I8V1NwVBKA1awpLo/ZQNwNgG0YhIl2JV6Pj+1GT187ICBi3e/wDhNhm8ip0vMHwqbcBcHh20UpEzKiDB/A3qKCTVK60IBBGybgJQ7HoGtML4kn8gc1lsOmlWoV7v4gGnAPLy/OpLzleman878Vo4FMtK+z19nr7PX2evs9fZ6+z19nr7PX2evs9fZ6+z19nr7PX2euJoDg0/63f/2gAIAQEAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHAAAAAAAAAAAAKwAAAAAAAAAACAAAAAAAAAAAAJGAAAAAAAAAAAsGgAAAAAAAAAAgAAAAAAAAAAABAFAAAAAAAAAAKAKAAAAAAAAAA0AEAAAAAAAAABIAoAAAAAAAAAAAAAAAAAAAAAAIAEAAAAAAAAAAAASAAAAAAAAAABAgAAAAAAAAAAEGwAAAAAAAAABIqAAAAAAAAAACFIAAAAAAAAAAAgAAAAAAAAAAABEAAAAAAAABAAAAAQAAAAAABAAAAAAAAAAAACAAAAEAAAAAAACAAAAQAAAAAAAEAAAAgAAAAAAAGAAAAAAAAAAAAGAAAQAAAAAAAAFAAAgAAAAAAAAdAAMAAAAAAAAAAwBAAAAAAAAABgMAAAAAAAAAABIAAAAAAAAAAADFaAAAAAAAAAABgVAAAAAAAAAABAAgAAAAAAAAAAGJwAAAAAAAAAAH2gAAAAAAAAAAABgAAAAAAAAAAAFAAAAAAAAAAAAaAAAAAAAAAAABgAAAAAAAAAAGEQAAAAAAAAAAIUAAAAAAAAAACggAAAAAAAAAAHAAAAAAAAAAAAMAAAAAAAAAAAAWAAAAAAAAAAAAlQAAAAAAAAAAARIAAAAAAAAAAAA6AAAAAAAAAAAAYAAAAAAAAAAAAIAAAAAAAAAADMEAAAAAAAAAAICAAAAAAAAAAGABAAAAAAAAAAYAAAAAAAAAAAA//wDgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAH//xAAsEAEAAQEFBgcBAQEBAAAAAAABEQAhMUFRYRBxgZGhsSBQYMHR8PEwQOGQ/9oACAEBAAE/EP8A0tNkHNRS8IZAaESRE09EBhaUQBmtXO1IkN9/BZrRQ3uOJxLXFpiyXpTzqUuaNIDHtDQ4gsHPo6qfbrJVd+J1oRBSEnE9CGqG9vWAGK5UVgOOa3i6XHXYCgBVuChjddBvGKhQGY/YpQM4lcnaWcjNp3h7ltTVwZTxM2rn6CbiO6gKcooklzNq/wCbLJLDLPMG5q8qJGy2Qb1btTLV5DyaZZ9peXVXcEq/7I5IuDow7PY4uZzNKJmDK8Y0cP8AnoG0Zoq1dwFu9MtiN1V7CxmINxi1d4iuDCSJROygtr9qHHY81OYVTfvL+GtGiEIXI+fxZQEeA4sHGlZpBimWpjSaWBauXWKD8m6gID+B/HQxMk1G2gGtk8mA0SHjsnLtPehD1A4efrAkL++I5bDbWRXl/IRgJmMVseQOGxmmwTJSPZy8/t6Dxqtdhw2DFxqhaHQfyhFczo/EbEOuM817efIRMAStI+ybsKg5RsEpdJzl/kMtcDSDYSGsc8Q9/PoJkr4Dq7REzOaKHt/IJ6kHJ57HNsRYWKt6rt58JrxALcDjbe6G3IhVSAW4Slmq/wAR/uBgBLWGPHJgcCDhsS0RFk2dAePnqKzhrxcKahLw8AwyUgUqy9sZdUh4/wAJE8CLU4t7wz2DIwIsC1choCxBrgCA89AJcz+HU28R4+dIKnFzaP8AlKhe1am92JY4AFt9PFbuDz5z0vAZ8IphEBuI9zxIwEZFpnpmaYImwNyDANgVgDBK8G/HTeUZYEAEAefQpX2whY84priyLxLza5ZAAXrQxwQjjGVzXwtq0sDOqztfupvMy4BkGBpsc5AWb265GNWTtNXinFfQETyVAs+St47ZWiCpZ8tbw8BA0mACh5NsaB04Nb91OWRKmVdg6YxJx2bkY1GfFu8JPsegbQmsJKd3y0pnrXdlmsSnetd2WZgVZlVtIXvfY0NgIgASrhQ0Q2Ynri3HMqFoHtB7r9p+FbFwDjvuNbqwMbF6zXF19B4qNiHk1i4yIeRSJACVWwrB8V8tbvKadkC6SHXHi2sLyJA03AELzvuLQs30GF4BAGQehUJLYM62Fd1lKhjYwuBa4zX6FH6FH6FCkI2hGkQA321bcxbW9Qe3m6+DbatWSbmOE5V+A+a/AfNfgPmvwHzX4D5r8B81+A+a/AfNfgPmvwHzX4D5r8B81+A+a/AfNfgPmvwHzX4D5r8B81+A+a/AfNfgPmvwHzX4D5r8B81+A+a/AfNfgPmvwHzX4D5r8B81GLgg2Vt5gTRdaz5aMJY6wJCUQCSVjXcS53f7lhCERbncVu4PLwwJE4uL0Y5xTqQDQovH/YnWYw2U78dKMsCACAPMEMgEluGA788/9c5h2reFoVY7KWLVvW/zFAIgjYjjSpmQC8xNXT/T7rFzORrUNQYxXuTR1v8AMwrugSBvEpLrm0NQz7N3+dj6WBcMVcAzqa5E/wAO7zVBISRph3Tc2zyul26p5UEIyR/yCZvg3DM2h0oTLAg2+rlp5ueEcCLcEarN+WtWGYoRWWMdMmrUCWNYaL1v5/4W+FDCNxTeJ1pO6u3HOiwvCAbvOFNuOfaApumTAP8A00o+qC6LFpkQicLaexmvm1w8IpjNyIM0vOJV39BrtjF0pd1Wrj727qqegSH5lW8LvObhRW8GlVVvdkUS2pin+cNuZuC6hfxmmslZ59NKXULgZcGHpTqRWInOKRGEh18AxSwZdKZLO3dQQKfFu8kfAs60mGrz3I5rR4Fwg6edlfxLjRmwLapPbYG4YLeMoajPB08UTfSGd6ogyXO1kiVAL+DRsHZEegLroDCwz3Cd47DfcRhVOCCAD/sdN1BD2RkGYnh613Nv2Of0DYZSMRwTJG2lSEKKw9sxttkFLPiFZQQNjOc1nUoUFpjI8beqpHFj2V2OGXT4lq1Jxpj7tKlZcZDvNBmAoW/inoIsmWPqZOpV8AWA6fTpSQ9hYVqPgLBVyoaigMuROTJUKCmDeoDUAaxKoJNfl7lS4SmLA6lPCr/1iR1oAuXHHM9BWCCgjwAtpJLLKPtpSqKYgXgW8ymZFehHB/gZNcY+lIBHYwiNAW8Rq0RMhezg9B6EBQ61Mr7jLvFSKtGU6Gp76W3RWEnh1ItTLtM0KoBlD2VPpi9/Qs2gkQZEbSm2mFW+2a3mtAIAtIG5H0IuqCwiS0S6sluZ3U+QPLuFJhV2CyJcxhlSCQkjg0asBi3zkv4zQCwtsB3N3RT+2hhGo7GIMB1/2uGu/wBCda7m37HP4GorC9rk56KsCUmDkmiW0+BQgwiY1KQbO/YkXj6D613Nv2OfwiuEfYiIerlsuKF3FO76D613Nv2OfwJIIvAC9WmOWYOCZeJ6Rsl8LDksdAePoPrXc2/Y59jkwEqoCiSTuUt63HOphEli9X8abF9EFuy5zbj/AJR4TjXAID0FqP7urSr9GSExY7fsc9S/dSTCs2o5UxJ+bRuLja3uW7oaMW6zWoFpbxGPQMy8NiEm9d3aUIRLrg1v8opRb8VJ4tX/ANbe37HPR1Y2AnGiRANyXMYKbSV50Gx1q00RYxdDZzmjJgQBAHoELI5L+FxNxuWnfipSpxWoeTe5wrDjU4PvTxmw70VWKYN5hv2/Y560gxYt5+hUJJpGxdqMRQl6TYMpttyGhLJEAb9XXZ1rubfsc/oYkkQk4ig8ko566oBGHGU3xt613Nv2Of0MkeJi1N3Dc6RlQqEUS5KO0oGktPcW76uSgHG5ydKVH8WRZcFKLGyEJw2fY5/QyCIkjeU1EkpC6sWl26smuCnUzNSgpfjU74vosG3h1LHSgyBsY9xv6U760MPOCoLfIvCF5bj6IdvWHBum6l0xcHpNvWkEPuJnhY604wBevnSKbDmDDQkxuSEpgCcT3kcKTxDTnYeZX4LUg38J9DssAIqdYSiAexX0o96i0jedVPtSJZKBuCW2B1oxcCAFwE9aHEVxL9zqVLniy25VnWm3lYzi5pxkq4Y5s28MTUs9C9a7m37HPtRASxEkaGyhJb1uPKdafrsCw8wM7zOr92AsD9JMPQnWu5t+xz+KGWgtLJMFzucc6WkbeB3N2I4iegXpFapAUijN8z3U6fiiLrLPTaTq0RtBimdNgqwN1kVuQU0by88IiSOAtYunz+KAS/ndlrTt1IGXUuHGaeTJmVDdcOB4w9tYSEpUVwU0uOdNsWaZF28Y30fFpRInn6gKsBetKkWUEpoxa3b6Ye0yB/ouea/uJi3cqs5hLiuplqWeeqBKwFMTas2vEP07r/7McWxLhiJiOVR+IRNfzM1h55azkQucRdOeU19S96+pe9fUvevqXvX1L3r6l719S96+pe9fUvevqXvX1L3r6l719S96+pe9fUvevqXvUhqogB71b9f/AFu//9k="/>
    </div>

    <div class="header-qr">
        <img width="150" height="150" src="data:image/png;base64, {{$prescriptionQRCode}} ">

    </div>
    {{--    <div class="header-patient">--}}

    {{--        <p>{{ $patient->first_name }} {{ $patient->last_name }}<br>Age: {{ $patient->birth_date }}<br>Date: {{ $prescription->created_at->format('M d, Y') }}</p>--}}
    {{--    </div>--}}

</div>
<div id="doctor name" style="margin-top: 3em">
    <p style="display: inline ; font-size: 1em ; font-weight: bold ">Doctor :</p>
    <p style="display: inline"> {{ $medicalRecord->assignedDoctor->first_name }} {{$medicalRecord->assignedDoctor->last_name }}</p>

</div>
<div id="patient_info" style="margin-top: 10px;margin-bottom: 30px; display: table; width: 100%;">
    <div style="display: table-row;width: 30%">
        <div style="display: table-cell;">
            <p style="font-size: 1em; font-weight: bold; margin-right: 10px; display: inline;">Patient:</p>
            <p style="font-size: 1em; display: inline;">{{ $medicalRecord->patient->first_name }} {{ $medicalRecord->patient->last_name }}</p>
        </div>
        <div style="display: table-cell; width: 40%;">
        </div>
        <div style="display: table-cell;width: 30% ; text-align: center">
            <p style="font-size: 1em; font-weight: bold; margin-right: 10px; display: inline;">Age:</p>
            <p style="font-size: 1em; display: inline;">{{ \Carbon\Carbon::parse($medicalRecord->patient->birth_date)->age }} years</p>
        </div>
    </div>
</div>
<div  class="prescription-title" style="">Prescription</div>

<table class="medicines-table">
    <thead>
    <tr>
        <th>#</th>
        <th>Medicine</th>
        <th>Quantity</th>

    </tr>
    </thead>
    <tbody>
    @foreach($prescription->medicineRequests as $medicineRequest)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $medicineRequest->medicine->name }}</td>
            <td style="font-size: 18px">{{ $medicineRequest->quantity }}</td>

        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
