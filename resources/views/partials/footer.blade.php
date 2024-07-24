<footer id="footer">
    <div class="footer_top">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="footer_widget wow fadeInLeftBig">
            <h2>Flickr Images</h2>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="footer_widget wow fadeInDown">
            <h2>Tag</h2>
            <ul class="tag_nav">
              <li><a href="#">Games</a></li>
              <li><a href="#">Sports</a></li>
              <li><a href="#">Fashion</a></li>
              <li><a href="#">Business</a></li>
              <li><a href="#">Life &amp; Style</a></li>
              <li><a href="#">Technology</a></li>
              <li><a href="#">Photo</a></li>
              <li><a href="#">Slider</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="footer_widget wow fadeInRightBig">
            <h2>Contact</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            <address>
            Perfect News,1238 S . 123 St.Suite 25 Town City 3333,USA Phone: 123-326-789 Fax: 123-546-567
            </address>
          </div>
        </div>
      </div>
    </div>
    <div class="footer_bottom">
      <p class="copyright">Copyright &copy; 2045 <a href="index.html">NewsFeed</a></p>
      <p class="developer">Developed By Wpfreeware</p>
    </div>
  </footer>
</div>
<script src="{{asset('assets/js/jquery.min.js')}}"></script> 
<script src="{{asset('assets/js/wow.min.js')}}"></script> 
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script> 
<script src="{{asset('assets/js/slick.min.js')}}"></script> 
<script src="{{asset('assets/js/jquery.li-scroller.1.0.js')}}"></script> 
<script src="{{asset('assets/js/jquery.newsTicker.min.js')}}"></script> 
<script src="{{asset('assets/js/jquery.fancybox.pack.js')}}"></script> 
<script src="{{asset('assets/js/custom.js')}}"></script>
<script type="text/javascript">
  try{
    const loader = document.querySelector("#loading");
    document.getElementById('toggle-komentar').addEventListener('click', function() {
        const beritaId = document.querySelector('.container-admin').id;
      
        const komentarSection = document.querySelector('.container-komentar');
        if (komentarSection.style.display === 'none' || komentarSection.style.display === '') {
            komentarSection.style.display = 'block';
            getKomentar(beritaId);
        } else {
            komentarSection.style.display = 'none';
        }
    });
}catch(error){

}



// showing loading
function displayLoading() {
    loader.classList.add("display");
    // to stop loading after some time
    setTimeout(() => {
        loader.classList.remove("display");
    }, 5000);
}

// hiding loading 
function hideLoading() {
    loader.classList.remove("display");
}


function updateUIAfterLogin(user) {
    const profile = document.querySelector('.header_top_right').children[2];
    if (profile) {
        profile.style.display = "block"; // Ubah ID jika perlu
        profile.children[0].innerHTML = `Hello, ${user}`; // Perbarui nama pengguna
        profile.parentNode.children[1].style.display = "none"
    }
}

function updateUIAfterLogout() {
    const profile = document.querySelector('.header_top_right').children[1];
    if (profile) {
        profile.style.display = "block"; // Ubah ID jika perlu
        profile.parentNode.children[2].style.display = 'none' // Set data target untuk login

    }
}


(function() {
    const userToken = localStorage.getItem('userToken');
    
    if (!userToken && "{{ $user['nama'] }}") {
        localStorage.setItem('userToken', "{{ $user['secret'] }}");
        updateUIAfterLogin("{{ $user['nama'] }}");
    } else if (userToken) {
        updateUIAfterLogin("{{ $user['nama'] }}");
        fetch('/api/me', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${userToken}`,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                localStorage.removeItem('userToken');
                throw new Error('Invalid token');
            }
        })
        .then(user => {
            updateUIAfterLogin(user.nama);
        })
        .catch(error => {
            alert('Silahkan login kembali')
            
            localStorage.removeItem('userToken');
            updateUIAfterLogout();
        });
    } else {
        updateUIAfterLogout();
    }
})();


document.getElementById('logoutForm').addEventListener('click', function(event){
    event.preventDefault()
    fetch(this.action, {
      method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('userToken')}`,
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Hapus token dari localStorage
            localStorage.removeItem('userToken');
            // updateUIAfterLogout();
            window.location.href ="/";
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
})

async function getKomentar(beritaId) {
    const komentarList = document.getElementById('komentar-list');

    try {
        displayLoading();
        // Fetch data from API
        const response = await fetch(`{{ url('api/komentar') }}/${beritaId}`);
        const data = await response.json();
        
        // Kosongkan daftar komentar sebelumnya
        komentarList.innerHTML = '';  

        // Append new comments
        data.forEach(komentar => {
            const komentarCard = document.createElement('div');
            komentarCard.className = 'card mb-4';

            komentarCard.innerHTML = `
                <div class="card-body">
                    <p>${komentar.isi_komentar}</p>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-row align-items-center">
                            <img src="" alt="avatar" width="25" height="25" />
                            <p class="small mb-0 ms-2">${komentar.user}</p>
                        </div>
                        <div class="d-flex flex-row align-items-center">
                            <p class="small text-muted mb-0">${komentar.tanggal_komentar}</p>
                            <i class="far fa-thumbs-up mx-2 fa-xs text-body" style="margin-top: -0.16rem;"></i>
                        </div>
                    </div>
                </div>
            `;
            komentarList.appendChild(komentarCard);
        });
    } catch (error) {
        console.error('Error fetching comments:', error);
    } finally {
        // Sembunyikan spinner setelah data diterima
        hideLoading()
    }
}

function showLoginPopup() {
    var modal = new bootstrap.Modal(document.getElementById('modalLogin'));
    modal.show();
}
 try{
  document.getElementById('submitComment').addEventListener('click', function() {
    const userToken = localStorage.getItem('userToken'); // Check if user token exists
    const beritaId = document.querySelector('.container-admin').id;
    const commentInput = document.getElementById('addANote');
    const comment = commentInput.value;
    const loader = document.getElementById('loader');
    
    if (!userToken) {
        // User is not logged in, show login popup
        $('#modalLogin').modal({
            show: 'true'
        }); 
        return; // Stop further execution
    }
    
    if (comment) {
        displayLoading();

        fetch(`{{url('api/komentar/create')}}/${beritaId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${userToken}`,
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
            },
            body: JSON.stringify({ isi_komentar: comment })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message)
            commentInput.value = ''; // Clear input
            buatKomentarBaru();
            hideLoading();
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
        });
    }
});
 }catch(err){}

function buatKomentarBaru(){
    const inputan = document.getElementById('addNote').value
    const komentarList = document.getElementById('komentar-list');
    const komentarCard = document.createElement('div');

    komentarCard.className = 'card mb-4';
    komentarCard.innerHTML = `
                <div class="card-body">
                    <p>${inputan}</p>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-row align-items-center">
                            <img src="" alt="avatar" width="25" height="25" />
                            <p class="small mb-0 ms-2">{{ $user['nama'] }}</p>
                        </div>
                        <div class="d-flex flex-row align-items-center">
                            <p class="small text-muted mb-0">Beberapa saat yang lalu</p>
                            <i class="far fa-thumbs-up mx-2 fa-xs text-body" style="margin-top: -0.16rem;"></i>
                        </div>
                    </div>
                </div>
            `;
    komentarList.appendChild(komentarCard);
}
function displayLoading() {
    loader.classList.add("display");
    // to stop loading after some time
    setTimeout(() => {
        loader.classList.remove("display");
    }, 5000);
}

// hiding loading 
function hideLoading() {
    loader.classList.remove("display");
}

const formContent = document.getElementById('formContent');
      const formFooter = document.getElementById('formFooter');
      const authForm = document.getElementById('authForm');
  
      // Initial content for login form
      const loginContent = `
        <p>Please login to your account</p>
        <div data-mdb-input-init class="form-outline mb-4">
          <input type="email" name="username" id="form2Example11" class="form-control" placeholder="Email" required/>
          <label class="form-label" for="form2Example11">Email</label>
        </div>
        <div data-mdb-input-init class="form-outline mb-2">
          <input type="password" name="password" id="form2Example22" class="form-control" placeholder="Password" required/>
          <label class="form-label" for="form2Example22">Password</label>
        </div>
        <div class="text-center pt-1 mb-2 pb-1">
          <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Log in</button>
        </div>
      `;
  
      const loginFooter = `
        <p class="mb-0 me-2">Don't have an account?</p>
        <button id="createNew" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-danger">Create new</button>
      `;
  
      // Content for register form
      const registerContent = `
        <p>Please register your account</p>
        <div data-mdb-input-init class="form-outline mb-4">
          <input type="text" id="form2Example11" class="form-control" name="nama" placeholder="Nama" required/>
          <label class="form-label" for="form2Example11">Nama</label>
        </div>
        <div data-mdb-input-init class="form-outline mb-4">
          <input type="email" name="username" id="form2Example22" class="form-control" placeholder="Email" required/>
          <label class="form-label" for="form2Example22">Email</label>
        </div>
        <div data-mdb-input-init class="form-outline mb-4">
          <input type="password" name="password" id="form2Example33" class="form-control" placeholder="Password" required/>
          <label class="form-label" for="form2Example33">Password</label>
        </div>
        <div class="text-center pt-1 mb-5 pb-1">
          <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Register</button>
        </div>
      `;
  
      const registerFooter = `
        <p class="mb-0 me-2">Sudah punya akun?</p>
        <button id="backLogin" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-danger">Login</button>
      `;
  
      // Function to load login form
      function loadLoginForm() {
        authForm.parentNode.children[0].style.display ="block"
        authForm.action = '{{ route('api/login') }}';
        formContent.innerHTML = loginContent;
        formFooter.innerHTML = loginFooter;
        
        try{
          document.getElementById('createNew').addEventListener('click', loadRegisterForm);
        }catch(err){}
      }
  
      // Function to load register form
      function loadRegisterForm() {
        authForm.parentNode.children[0].style.display ="none"
        authForm.action = '{{ route('api/register') }}';
        formContent.innerHTML = registerContent;
        formFooter.innerHTML = registerFooter;
        
        try{
          document.getElementById('backLogin').addEventListener('click', loadLoginForm);
        }catch(err){}
      }
  
      // Load initial login form
      loadLoginForm();
</script>
</body>
</html>