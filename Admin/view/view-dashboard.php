<?php
require_once '../middleware/AuthMiddleware.php';
AuthMiddleware::requireRole(['Administrador', 'Empleado']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Reportes</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Adminlte/dist/css/adminlte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" crossorigin="anonymous">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php
        include 'nav.php';
        include 'menu-lateral.php';
        ?>

        <div class="container my-4">
            <h2 class="text-center mb-4">Dashboard</h2>

            <!-- Widgets de Resumen -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3>150</h3>
                            <p>New Orders</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                        </svg>
                        <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>53<sup class="fs-5">%</sup></h3>
                            <p>Bounce Rate</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"></path>
                        </svg>
                        <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>44</h3>
                            <p>User Registrations</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                        </svg>
                        <a href="#" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6"> <!--begin::Small Box Widget 4-->
                        <div class="small-box text-bg-danger">
                            <div class="inner">
                                <h3>65</h3>
                                <p>Unique Visitors</p>
                            </div> <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
                            </svg> <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                More info <i class="bi bi-link-45deg"></i> </a>
                        </div> <!--end::Small Box Widget 4-->
                    </div> <!--end::Col-->
            </div>

            <!-- Filtros de Reportes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Filtrar Reportes</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label for="dateRange" class="form-label">Rango de Fecha</label>
                            <input type="date" id="startDate" class="form-control mb-1" placeholder="Fecha inicio">
                            <input type="date" id="endDate" class="form-control" placeholder="Fecha fin">
                        </div>
                        <div class="col-md-4">
                            <label for="reportType" class="form-label">Tipo de Reporte</label>
                            <select id="reportType" class="form-select">
                                <option selected disabled>Seleccione un reporte</option>
                                <option value="ventas">Ventas</option>
                                <option value="usuarios">Usuarios</option>
                                <option value="productos">Productos</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Generar Reporte</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Gráficos de Reportes -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Ventas Mensuales</h5>
                        </div>
                        <div class="card-body">
                            <div id="monthly-sales-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Ventas por Categoría</h5>
                        </div>
                        <div class="card-body">
                            <div id="category-sales-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Reportes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Tabla de Reportes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Ventas</th>
                                    <th>Usuarios Nuevos</th>
                                    <th>Productos Vendidos</th>
                                    <th>Total Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos de ejemplo -->
                                <tr>
                                    <td>2023-10-01</td>
                                    <td>150</td>
                                    <td>20</td>
                                    <td>75</td>
                                    <td>$3,500.00</td>
                                </tr>
                                <tr>
                                    <td>2023-10-02</td>
                                    <td>180</td>
                                    <td>15</td>
                                    <td>90</td>
                                    <td>$4,200.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once 'footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        // Gráficos de ejemplo con ApexCharts
        var optionsSales = {
            chart: { type: 'line', height: 280 },
            series: [{ name: "Ventas", data: [50, 100, 70, 200, 150, 300, 280] }],
            xaxis: { categories: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul"] }
        };
        var chartSales = new ApexCharts(document.querySelector("#monthly-sales-chart"), optionsSales);
        chartSales.render();

        var optionsCategory = {
            chart: { type: 'pie', height: 280 },
            labels: ["Ropa", "Accesorios", "Calzado"],
            series: [60, 25, 15]
        };
        var chartCategory = new ApexCharts(document.querySelector("#category-sales-chart"), optionsCategory);
        chartCategory.render();
    </script>
</body>

</html>
