<aside class="aside-menu">
  <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab"><i class="icon-list"></i></a>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><i class="icon-speech"></i></a>
      </li>
      <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="icon-settings"></i></a>
      </li>
  </ul>
  <!-- Tab panes -->
  <div class="tab-content">
      <div class="tab-pane active" id="timeline" role="tabpanel">
          {{-- <div class="callout m-a-0 p-y-h text-muted text-xs-center bg-faded text-uppercase">
              <small><b>Today</b>
              </small>
          </div>
          <hr class="transparent m-x-1 m-y-0"> --}}
          @foreach ($adminNotifications as $notification)
          <div class="callout callout-warning m-a-0 p-y-1">
            {{-- <div class="avatar pull-xs-right">
                <i class="fa fa-user"></i>
            </div> --}}
            <div>
                <strong>{{ $notification->data['title'] }}</strong>
            </div>
            <div>
                <strong>{{ $notification->data['content'] }}</strong>
            </div>
            <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; {{ $notification->data['time'] }}</small>
          </div>
          <hr class="m-x-1 m-y-0">
          @endforeach
          
          <hr class="transparent m-x-1 m-y-0">
         
      </div>
      <div class="tab-pane p-a-1" id="messages" role="tabpanel">
          <div class="message">
              <div class="p-y-1 p-b-3 m-r-1 pull-left">
                  <div class="avatar">
                      <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                      <span class="avatar-status tag-success"></span>
                  </div>
              </div>
              <div>
                  <small class="text-muted">Lukasz Holeczek</small>
                  <small class="text-muted pull-left m-t-q">1:52 PM</small>
              </div>
              <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
              <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
              <div class="p-y-1 p-b-3 m-r-1 pull-left">
                  <div class="avatar">
                      <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                      <span class="avatar-status tag-success"></span>
                  </div>
              </div>
              <div>
                  <small class="text-muted">Lukasz Holeczek</small>
                  <small class="text-muted pull-left m-t-q">1:52 PM</small>
              </div>
              <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
              <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
              <div class="p-y-1 p-b-3 m-r-1 pull-left">
                  <div class="avatar">
                      <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                      <span class="avatar-status tag-success"></span>
                  </div>
              </div>
              <div>
                  <small class="text-muted">Lukasz Holeczek</small>
                  <small class="text-muted pull-right m-t-q">1:52 PM</small>
              </div>
              <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
              <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
              <div class="p-y-1 p-b-3 m-r-1 pull-left">
                  <div class="avatar">
                      <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                      <span class="avatar-status tag-success"></span>
                  </div>
              </div>
              <div>
                  <small class="text-muted">Lukasz Holeczek</small>
                  <small class="text-muted pull-right m-t-q">1:52 PM</small>
              </div>
              <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
              <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
              <div class="p-y-1 p-b-3 m-r-1 pull-left">
                  <div class="avatar">
                      <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                      <span class="avatar-status tag-success"></span>
                  </div>
              </div>
              <div>
                  <small class="text-muted">Lukasz Holeczek</small>
                  <small class="text-muted pull-right m-t-q">1:52 PM</small>
              </div>
              <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
              <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
      </div>
      <div class="tab-pane p-a-1" id="settings" role="tabpanel">
          <h6>Settings</h6>
          <div class="aside-options">
              <div class="clearfix m-t-2">
                  <small><b>Option 1</b>
                  </small>
                  <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                      <input type="checkbox" class="switch-input" checked>
                      <span class="switch-label" data-on="On" data-off="Off"></span>
                      <span class="switch-handle"></span>
                  </label>
              </div>
              <div>
                  <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small>
              </div>
          </div>
          <div class="aside-options">
              <div class="clearfix m-t-1">
                  <small><b>Option 2</b>
                  </small>
                  <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                      <input type="checkbox" class="switch-input">
                      <span class="switch-label" data-on="On" data-off="Off"></span>
                      <span class="switch-handle"></span>
                  </label>
              </div>
              <div>
                  <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small>
              </div>
          </div>
          <div class="aside-options">
              <div class="clearfix m-t-1">
                  <small><b>Option 3</b>
                  </small>
                  <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                      <input type="checkbox" class="switch-input">
                      <span class="switch-label" data-on="On" data-off="Off"></span>
                      <span class="switch-handle"></span>
                  </label>
              </div>
          </div>
          <div class="aside-options">
              <div class="clearfix m-t-1">
                  <small><b>Option 4</b>
                  </small>
                  <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                      <input type="checkbox" class="switch-input" checked>
                      <span class="switch-label" data-on="On" data-off="Off"></span>
                      <span class="switch-handle"></span>
                  </label>
              </div>
          </div>
          <hr>
          <h6>System Utilization</h6>
          <div class="text-uppercase m-b-q m-t-2">
              <small><b>CPU Usage</b>
              </small>
          </div>
          <progress class="progress progress-xs progress-info m-a-0" value="25" max="100">25%</progress>
          <small class="text-muted">348 Processes. 1/4 Cores.</small>
          <div class="text-uppercase m-b-q m-t-h">
              <small><b>Memory Usage</b>
              </small>
          </div>
          <progress class="progress progress-xs progress-warning m-a-0" value="70" max="100">70%</progress>
          <small class="text-muted">11444GB/16384MB</small>
          <div class="text-uppercase m-b-q m-t-h">
              <small><b>SSD 1 Usage</b>
              </small>
          </div>
          <progress class="progress progress-xs progress-danger m-a-0" value="95" max="100">95%</progress>
          <small class="text-muted">243GB/256GB</small>
          <div class="text-uppercase m-b-q m-t-h">
              <small><b>SSD 2 Usage</b>
              </small>
          </div>
          <progress class="progress progress-xs progress-success m-a-0" value="10" max="100">10%</progress>
          <small class="text-muted">25GB/256GB</small>
      </div>
  </div>
</aside>