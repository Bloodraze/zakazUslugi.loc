<?php
require_once __DIR__ . '/src/init_add-application.php';
require_once __DIR__ . '/src/header.php';
?>

                <nav aria-label="breadcrumb">
                    <ol id="w4" class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="account.html">заявки</a></li>
                    </ol>
                </nav>
                <div class="application-index">
    
                    <h1>Заявка на посещение</h1>
    
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="1000">
 
                        <div id="w1" class="list-view">
                            <div class="d-flex flex-wrap justify-content-between layout-card">
                                
                                <div class="item" data-key="9">
                                    <div class="card" style="width: 18rem;">
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                sdsdsfdfdf </h5>
                                            <p class="card-text">
                                                fdsfdsfdfdsfdsf</p>
                                            <div class="card-text">
                                                <div class="opacity-50">
                                                    дата и время посещения:
                                                </div>
                                                25.05.2024 15-30
                                            </div>
                                            <div class="card-text">
                                                <div class="opacity-50">
                                                    дата и время создания:
                                                </div>
                                                25.05.2024 15-30
                                                 
                                            </div>
                                            <div class="card-text">
                                                <div class="opacity-50">
                                                    статус:
                                                </div>
                                                На посещение
                                            </div>
                                            
                                            <a class="btn btn-danger" href="/account/application/view?id=9">отменить</a>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php include 'src/footer.php'; ?>