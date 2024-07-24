<!-- Modal -->
<div class="modal modal-auth" style="overflow:hidden;" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="width: 65%; margin:auto;">
        <div class="card-body p-md-5">
          <div class="text-center">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp" style="width: 185px;" alt="logo">
            <h4 class="mt-1 mb-5">We are The Lotus Team</h4>
          </div>
          <form  id="authForm" action="{{ route('api/login') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="formContent">
              <!-- Form content will be dynamically inserted here -->
            </div>
            <div id="formFooter" class="d-flex align-items-center justify-content-center">
              <!-- Form footer content will be dynamically inserted here -->
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal modal-auth" style="overflow:hidden;" id="modalLogout" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="width: 65%; margin:auto;">
        <div class="card-body p-md-5">
          
          <form  id="logoutForm" action="{{ route('api/logout')}}">
            @csrf
            <div id="formContent">
              <p>Apakah Anda Yakin ingin Logout??</p>
            </div>
            <div id="formFooter" class="d-flex align-items-center justify-content-center">
              <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Logout</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>