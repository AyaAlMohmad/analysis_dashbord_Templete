<div class="navbar-wrapper">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                    href="#"><i class="ft-menu font-large-1"></i></a></li>
            <li class="nav-item">
                <a class="navbar-brand" href="{{ route('admin.index') }}">
                    <img class="brand-logo" alt="Logo" src="{{ asset('build/logo.png') }}"
                        style="max-width: 100px; max-height: 100px">

                </a>
            </li>
            <li class="nav-item d-md-none">
                <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i
                        class="fa fa-ellipsis-v"></i></a>
            </li>
        </ul>
    </div>
    <div class="navbar-container content">
        <div class="collapse navbar-collapse" id="navbar-mobile">
            <ul class="nav navbar-nav mr-auto float-left">
                <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                        href="#"><i class="ft-menu"></i></a></li>
                {{-- <li class="dropdown nav-item mega-dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown">Mega</a>
            <ul class="mega-dropdown-menu dropdown-menu row">
              <li class="col-md-2">
                <h6 class="dropdown-menu-header text-uppercase mb-1"><i class="fa fa-newspaper-o"></i> News</h6>
                <div id="mega-menu-carousel-example">
                  <div>
                    <img class="rounded img-fluid mb-1" src="../../../app-assets/images/slider/slider-2.png"
                    alt="First slide"><a class="news-title mb-0" href="#">Poster Frame PSD</a>
                    <p class="news-content">
                      <span class="font-small-2">January 26, 2016</span>
                    </p>
                  </div>
                </div>
              </li>
              <li class="col-md-3">
                <h6 class="dropdown-menu-header text-uppercase"><i class="fa fa-random"></i> Drill down menu</h6>
                <ul class="drilldown-menu">
                  <li class="menu-list">
                    <ul>
                      <li>
                        <a class="dropdown-item" href="layout-2-columns.html"><i class="ft-file"></i> Page layouts & Templates</a>
                      </li>
                      <li><a href="#"><i class="ft-align-left"></i> Multi level menu</a>
                        <ul>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-bookmark-o"></i>  Second level</a></li>
                          <li><a href="#"><i class="fa fa-lemon-o"></i> Second level menu</a>
                            <ul>
                              <li><a class="dropdown-item" href="#"><i class="fa fa-heart-o"></i>  Third level</a></li>
                              <li><a class="dropdown-item" href="#"><i class="fa fa-file-o"></i> Third level</a></li>
                              <li><a class="dropdown-item" href="#"><i class="fa fa-trash-o"></i> Third level</a></li>
                              <li><a class="dropdown-item" href="#"><i class="fa fa-clock-o"></i> Third level</a></li>
                            </ul>
                          </li>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-hdd-o"></i> Second level, third link</a></li>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-floppy-o"></i> Second level, fourth link</a></li>
                        </ul>
                      </li>
                      <li>
                        <a class="dropdown-item" href="color-palette-primary.html"><i class="ft-camera"></i> Color pallet system</a>
                      </li>
                      <li><a class="dropdown-item" href="sk-2-columns.html"><i class="ft-edit"></i> Page starter kit</a></li>
                      <li><a class="dropdown-item" href="changelog.html"><i class="ft-minimize-2"></i> Change log</a></li>
                      <li>
                        <a class="dropdown-item" href="https://pixinvent.ticksy.com/"><i class="fa fa-life-ring"></i> Customer support center</a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li class="col-md-3">
                <h6 class="dropdown-menu-header text-uppercase"><i class="fa fa-list-ul"></i> Accordion</h6>
                <div id="accordionWrap" role="tablist" aria-multiselectable="true">
                  <div class="card border-0 box-shadow-0 collapse-icon accordion-icon-rotate">
                    <div class="card-header p-0 pb-2 border-0" id="headingOne" role="tab"><a data-toggle="collapse" data-parent="#accordionWrap" href="#accordionOne"
                      aria-expanded="true" aria-controls="accordionOne">Accordion Item #1</a></div>
                    <div class="card-collapse collapse show" id="accordionOne" role="tabpanel" aria-labelledby="headingOne"
                    aria-expanded="true">
                      <div class="card-content">
                        <p class="accordion-text text-small-3">Caramels dessert chocolate cake pastry jujubes bonbon.
                          Jelly wafer jelly beans. Caramels chocolate cake liquorice
                          cake wafer jelly beans croissant apple pie.</p>
                      </div>
                    </div>
                    <div class="card-header p-0 pb-2 border-0" id="headingTwo" role="tab"><a class="collapsed" data-toggle="collapse" data-parent="#accordionWrap"
                      href="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">Accordion Item #2</a></div>
                    <div class="card-collapse collapse" id="accordionTwo" role="tabpanel" aria-labelledby="headingTwo"
                    aria-expanded="false">
                      <div class="card-content">
                        <p class="accordion-text">Sugar plum bear claw oat cake chocolate jelly tiramisu
                          dessert pie. Tiramisu macaroon muffin jelly marshmallow
                          cake. Pastry oat cake chupa chups.</p>
                      </div>
                    </div>
                    <div class="card-header p-0 pb-2 border-0" id="headingThree" role="tab"><a class="collapsed" data-toggle="collapse" data-parent="#accordionWrap"
                      href="#accordionThree" aria-expanded="false" aria-controls="accordionThree">Accordion Item #3</a></div>
                    <div class="card-collapse collapse" id="accordionThree" role="tabpanel" aria-labelledby="headingThree"
                    aria-expanded="false">
                      <div class="card-content">
                        <p class="accordion-text">Candy cupcake sugar plum oat cake wafer marzipan jujubes
                          lollipop macaroon. Cake dragée jujubes donut chocolate
                          bar chocolate cake cupcake chocolate topping.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="col-md-4">
                <h6 class="dropdown-menu-header text-uppercase mb-1"><i class="fa fa-envelope-o"></i> Contact Us</h6>
                <form class="form form-horizontal">
                  <div class="form-body">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label" for="inputName1">Name</label>
                      <div class="col-sm-9">
                        <div class="position-relative has-icon-left">
                          <input class="form-control" type="text" id="inputName1" placeholder="John Doe">
                          <div class="form-control-position pl-1"><i class="fa fa-user-o"></i></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label" for="inputEmail1">Email</label>
                      <div class="col-sm-9">
                        <div class="position-relative has-icon-left">
                          <input class="form-control" type="email" id="inputEmail1" placeholder="john@example.com">
                          <div class="form-control-position pl-1"><i class="fa fa-envelope-o"></i></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label" for="inputMessage1">Message</label>
                      <div class="col-sm-9">
                        <div class="position-relative has-icon-left">
                          <textarea class="form-control" id="inputMessage1" rows="2" placeholder="Simple Textarea"></textarea>
                          <div class="form-control-position pl-1"><i class="fa fa-commenting-o"></i></div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 mb-1">
                        <button class="btn btn-primary float-right" type="button"><i class="fa fa-paper-plane-o"></i> Send</button>
                      </div>
                    </div>
                  </div>
                </form>
              </li>
            </ul>
          </li> --}}
                <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i
                            class="ficon ft-maximize"></i></a></li>
                {{-- <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"><i class="ficon ft-search"></i></a>
            <div class="search-input">
              <input class="input" type="text" placeholder="Explore Stack...">
            </div>
          </li> --}}
            </ul>
            <ul class="nav navbar-nav float-right">
                {{-- <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag"
                        href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            class="flag-icon flag-icon-gb"></i><span class="selected-language"></span></a>
                    <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item"
                            href="#"><i class="flag-icon flag-icon-gb"></i> English</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-cn"></i> Chinese</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-de"></i> German</a>
                    </div>
                </li>
                --}}
                <li class="dropdown dropdown-language nav-item">
                  <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="flag-icon flag-icon-gb"></i>
                      <span class="selected-language"></span>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                      <a class="dropdown-item" href="#">
                          <i class="flag-icon flag-icon-gb"></i> English
                      </a>
                      <a class="dropdown-item" href="#">
                          <i class="flag-icon flag-icon-sa"></i> العربية
                      </a>
                  </div>
              </li>
              
                <li class="dropdown dropdown-user nav-item">
                    <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                        <span class="avatar avatar-online">
                            <img src="{{ asset('app-assets/images/portrait/small/avatar5.png') }}"
                                alt="avatar"><i></i></span>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ft-user"></i> Edit
                            Profile</a>

                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            <a class="dropdown-item" href="route('logout')"
                                onclick="event.preventDefault();
                          this.closest('form').submit();"><i
                                    class="ft-power"></i> Logout</a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
