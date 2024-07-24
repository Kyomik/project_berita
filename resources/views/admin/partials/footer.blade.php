<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <script defer>
    	const body = document.querySelector("body"),
      modeToggle = body.querySelector(".mode-toggle");
      sidebar = body.querySelector(".nav");
      sidebarToggle = body.querySelector(".sidebar-toggle");


let getMode = localStorage.getItem("mode");
if(getMode && getMode ==="dark"){
    body.classList.toggle("dark");
}

let getStatus = localStorage.getItem("status");
if(getStatus && getStatus ==="close"){
    sidebar.classList.toggle("close");
}

// try{
  // modeToggle.addEventListener("click", () =>{
  //     body.classList.toggle("dark");
  //     if(body.classList.contains("dark")){
  //         localStorage.setItem("mode", "dark");
  //     }else{
  //         localStorage.setItem("mode", "light");
  //     }
  // });

  sidebarToggle.addEventListener("click", () => {
      sidebar.classList.toggle("close");
      if(sidebar.classList.contains("close")){
        document.querySelector('.sub-nav li a.btn').style.padding = "0px"
        document.querySelector('.sub-nav li a.btn').style.width = "45px"
        document.querySelector('.sub-nav li a.btn span').style.display = "none"
          localStorage.setItem("status", "close");
      }else{
        document.querySelector('.sub-nav li a.btn').style.padding = "12px"
        document.querySelector('.sub-nav li a.btn').style.width = "100%"
        document.querySelector('.sub-nav li a.btn span').style.display = "inline-block"

          localStorage.setItem("status", "open");
      }
  })
// }catch(err){}

function toggleSubMenu(event) {
    event.preventDefault();
    const parentLi = event.target.closest('li');
    parentLi.classList.toggle('show-sub-menu');
}

//kategori manage

try{
  let selectedButton = null;
  let initialCategories = [];
  let deleteIds = [];

  const SAVE_CHANGES_BTN_ID = 'save-changes-btn';
  const SAVE_CANCEL_BTN_ID = 'save-cancel-btn';
  const DELETE_KATEGORI_BTN_ID = 'delete-kategori-btn';
  const MODAL_EDIT_ID = '#modal-edit';
  const MODAL_TAMBAH_ID = '#modal-tambah';

  function getLastId() {
    let maxId = 0;
    document.querySelectorAll('.kategori-btn').forEach(button => {
      const id = parseInt(button.getAttribute('data-id'), 10);
      if (id > maxId) {
        maxId = id;
      }
    });
    return maxId;
  }

  // Menyimpan data kategori awal saat halaman dimuat
  document.querySelectorAll('.kategori-btn').forEach(button => {
    initialCategories.push({
      id: button.getAttribute('data-id'),
      text: button.textContent.trim()
    });
  });

  // Menampilkan tombol "Save Changes" dan "Cancel ALL"
  function showSaveChangesButtons() {
    document.getElementById(SAVE_CHANGES_BTN_ID).classList.remove('d-none');
    document.getElementById(SAVE_CANCEL_BTN_ID).classList.remove('d-none');
  }

  // Menyembunyikan tombol "Save Changes" dan "Cancel ALL"
  function hideSaveChangesButtons() {
    document.getElementById(SAVE_CHANGES_BTN_ID).classList.add('d-none');
    document.getElementById(SAVE_CANCEL_BTN_ID).classList.add('d-none');
  }

  // Mengembalikan kategori ke keadaan semula
  function cancelAllChanges() {
    const dataKategori = document.querySelector('.data-kategori');

    // Hapus semua kategori yang ada
    document.querySelectorAll('.kategori-btn').forEach(button => {
      button.remove();
    });

    // Tambahkan kembali kategori dari initialCategories
    initialCategories.forEach(category => {
      const newButton = createCategoryButton(category.id, category.text);
      const tambahKategoriPlaceholder = document.getElementById('tambah-kategori-placeholder');
      dataKategori.insertBefore(newButton, tambahKategoriPlaceholder);
    });

    // Reset tombol "Cancel ALL" dan "Save Changes"
    hideSaveChangesButtons();
    deleteIds = [];  // Kosongkan array deleteIds
  }

  // Cek apakah kategori ada di initialCategories
  function isInitialCategory(id) {
    return initialCategories.some(category => category.id === id);
  }

  function createCategoryButton(id, text) {
    const newButton = document.createElement('button');
    newButton.type = 'button';
    newButton.className = 'btn btn-primary kategori-btn';
    newButton.setAttribute('data-id', id);
    newButton.setAttribute('data-toggle', 'modal');
    newButton.setAttribute('data-target', MODAL_EDIT_ID);
    newButton.textContent = text;

    newButton.addEventListener('click', function() {
      selectedButton = this;
      document.getElementById('editable-input').value = this.textContent.trim();
    });

    return newButton;
  }

  // Event listener untuk tombol delete yang berubah menjadi cancel
  document.getElementById(DELETE_KATEGORI_BTN_ID).addEventListener('click', function() {
    const deleteButton = document.getElementById(DELETE_KATEGORI_BTN_ID);
    if (deleteButton.textContent.trim() === 'Delete') {
      document.querySelectorAll('.kategori-btn').forEach(button => {
        button.classList.remove('btn-primary');
        button.classList.add('btn-danger');
        button.setAttribute('data-target', '');  // Hapus data-target
      });

      deleteButton.textContent = 'Cancel';
    } else {
      document.querySelectorAll('.kategori-btn').forEach(button => {
        button.classList.remove('btn-danger');
        button.classList.add('btn-primary');
        button.setAttribute('data-target', MODAL_EDIT_ID);
      });

      deleteButton.textContent = 'Delete';
    }
  });

  // Pilih tombol kategori dan tambahkan event listener
  document.querySelector('.data-kategori').addEventListener('click', function(event) {
    if (event.target.classList.contains('kategori-btn') && event.target.classList.contains('btn-danger')) {
      const kategoriText = event.target.textContent.trim();
      const confirmDelete = confirm(`Apakah Anda yakin ingin menghapus kategori "${kategoriText}"?`);
      if (confirmDelete) {
        const id = event.target.getAttribute('data-id');
        if (isInitialCategory(id)) {
          deleteIds.push(id);  // Simpan id kategori yang dihapus jika ada di initialCategories
        }
        event.target.remove();
        showSaveChangesButtons();  // Tampilkan tombol "Save Changes" dan "Cancel ALL"
      }
    } else if (event.target.classList.contains('kategori-btn')) {
      selectedButton = event.target;
      document.getElementById('editable-input').value = selectedButton.textContent.trim();
    }
  });

  // Event listener untuk mengedit kategori
  document.getElementById('edit-kategori-btn').addEventListener('click', function() {
    if (selectedButton) {
      const newKategoriText = document.getElementById('editable-input').value.trim();
      selectedButton.textContent = newKategoriText;
      $(MODAL_EDIT_ID).modal('hide');
      showSaveChangesButtons();  // Tampilkan tombol "Save Changes" dan "Cancel ALL"
    }
  });

  // Event listener untuk menambah kategori baru
  document.getElementById('tambah-kategori-btn').addEventListener('click', function() {
    let nextId = getLastId();
    const inputKategori = document.getElementById('input-kategori');
    const kategori = inputKategori.value.trim();
    if (kategori) {
      const dataKategori = document.querySelector('.data-kategori');
      const newButton = createCategoryButton(nextId += 1, kategori);
      const tambahKategoriPlaceholder = document.getElementById('tambah-kategori-placeholder');
      dataKategori.insertBefore(newButton, tambahKategoriPlaceholder);

      inputKategori.value = ''; // Reset input field
      $(MODAL_TAMBAH_ID).modal('hide'); // Hide modal
      showSaveChangesButtons();  // Tampilkan tombol "Save Changes" dan "Cancel ALL"
    } else {
      alert("Nama kategori tidak boleh kosong.");
    }
  });

  // Event listener untuk tombol Save Changes
  document.getElementById(SAVE_CHANGES_BTN_ID).addEventListener('click', function(event) {
    event.preventDefault();
    const kategoriData = [];
    let confirmated = confirm('Apakah anda yakin ingin menyipan perubahan??')
    if(confirmated){
      document.querySelectorAll('.data-kategori .kategori-btn').forEach(button => {
        kategoriData.push({ id_kategori: button.getAttribute('data-id'), nama_kategori: button.textContent.trim() });
      });

      fetch('{{ route("api/kategori/manage") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')  // Tambahkan token CSRF
        },
        body: JSON.stringify({ categories: kategoriData, deletedCategories: deleteIds })  // Tambahkan deleteIds ke payload
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        console.log(data)
        if (data.message == "success") {
          // alert('Changes saved successfully!');
          hideSaveChangesButtons();  // Sembunyikan tombol "Save Changes" dan "Cancel ALL"
          deleteIds = [];  // Kosongkan array deleteIds setelah penyimpanan
        } else {
          alert(data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving changes.');
      });
    }
  });

  // Event listener untuk tombol Cancel ALL
  document.getElementById(SAVE_CANCEL_BTN_ID).addEventListener('click', cancelAllChanges);
}catch(error){

}

  try{
    const paragrafContainer = document.getElementById('paragrafContainer');
    let paragrafCount = paragrafContainer.querySelector('.form-group').querySelectorAll('.form-control').length

    document.getElementById('addParagrafButton').addEventListener('click', function () {
        paragrafCount++;
        const newParagraf = document.createElement('div');
        newParagraf.classList.add('form-group');
        newParagraf.innerHTML = `
            <label>Paragraf ${paragrafCount}</label>
            <input type="hidden" name="id_paragraft[]" value="8998721">
            <textarea class="form-control" rows="3" name="isi_paragraft[]" id="paragraf${paragrafCount}"></textarea>
        `;
        paragrafContainer.appendChild(newParagraf);
    });

    // <input type="hidden" name="id_paragraf[]"> 

    
}catch(error){
  
}
try{
  document.getElementById('formUpload').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission
    fetchFeatures('Apakah Anda yakin ingin mengirimkan berita ini?', this)
  });
}catch(err){}
try{
  document.getElementById('formEdit').addEventListener('submit', function(event){
    event.preventDefault()
    fetchFeatures('Apakah Anda yakin ingin mengedit berita ini?', this)
  })
}catch(err){}
try{
  document.getElementById('createAdmin').addEventListener('submit', function(event){
    event.preventDefault()
    fetchFeatures('Apakah Anda yakin ingin menambah admin?', this)
  })
}catch(err){}
try{
  document.getElementById('editAdmin').addEventListener('submit', function(event){
    event.preventDefault()
    fetchFeatures('Apakah Anda yakin ingin mengedit admin?', this)
  })
}catch(err){}

function fetchFeatures(message, form) {
        if (confirm(message)) {
            const formData = new FormData(form);
            const userSecret = "{{ $user['secret'] }}"; // Inject token from Blade to JS

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Authorization': `Bearer ${userSecret}`,
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Terjadi kesalahan saat mengirim data.');
                }
            })
            .then(data => {
              console.log(data)
                if (data.redirect) {
                    alert(data.status)
                    window.location.href = `${data.redirect}/${userSecret}`;
                }
            })
            .catch(error => {
                alert(error.message);
            });
        }
    }

    document.getElementById('logoutForm').addEventListener('click', function(event){
      event.preventDefault()
      let confirmated = confirm('apakah anda yakin ingin logout?')
      if(confirmated){
        const userSecret = "{{ $user['secret'] }}"; // Inject token from Blade to JS
        fetch(this.action, {
          method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${userSecret}`,
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Hapus token dari localStorage
                // updateUIAfterLogout();
                window.location.href ="/";
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error('Error:', error));
      }
  })

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

try{
  document.getElementById('submitComment').addEventListener('click', function() {
      const beritaId = document.querySelector('.container-admin').id;
      const commentInput = document.getElementById('addANote');
      const comment = commentInput.value;
      const loader = document.getElementById('loader');
      const userSecret = "{{ $user['secret'] }}"; 
      if (comment) {
          displayLoading()

          fetch(`{{url('api/komentar/create')}}/${beritaId}`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'Authorization': `Bearer ${userSecret}`,
                  'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
              },
              body: JSON.stringify({ isi_komentar: comment })
          })
          .then(response => response.json())
          .then(data => {
              alert(data.message)

              hideLoading();
          })
          .catch(error => {
              hideLoading()
              console.error('Error:', error);
          });
      }
  });

}catch(err){

}
function buatKomentarBaru(data){
    const komentarList = document.getElementById('komentar-list');
    const komentarCard = document.createElement('div');

    komentarCard.className = 'card mb-4';
    komentarCard.innerHTML = `
                <div class="card-body">
                    <p>${data.isi_komentar}</p>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-row align-items-center">
                            <img src="" alt="avatar" width="25" height="25" />
                            <p class="small mb-0 ms-2">${data.username}</p>
                        </div>
                        <div class="d-flex flex-row align-items-center">
                            <p class="small text-muted mb-0">${data.tanggal_komentar}</p>
                            <i class="far fa-thumbs-up mx-2 fa-xs text-body" style="margin-top: -0.16rem;"></i>
                        </div>
                    </div>
                </div>
            `;
    komentarList.appendChild(komentarCard);
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

async function getKomentar(beritaId) {
  const userSecret = "{{ $user['secret'] }}"; // Inject token from Blade to JS

  const komentarList = document.getElementById('komentar-list');

    try {
        displayLoading();
        // Fetch data from API
        const response = await fetch(`{{ url('api/komentar') }}/${beritaId}`);
        const data = await response.json();
        
        // Kosongkan daftar komentar sebelumnya
        komentarList.innerHTML = ''; 
        
        if(data.length == 0)
          komentarList.innerHTML = 'belum ada komentar'
        else{
          // Append new comments
          data.forEach(komentar => {
            const komentarCard = document.createElement('div');
            komentarCard.className = 'card mb-4';

            buatKomentarBaru(komentar);
          });
        }
        
    } catch (error) {
        console.error('Error fetching comments:', error);
    } finally {
        // Sembunyikan spinner setelah data diterima
        hideLoading()
    }
}
  try{
    document.querySelector('.features').addEventListener('click', (event) => {
      const idBerita = event.target.getAttribute('data-id');
        // Cek apakah target dari event adalah tombol `btnKomentar`
        if (event.target.classList.contains('declined')) {
            event.preventDefault();

            fetchGET(`{{ url('api/berita/decline') }}/${idBerita}`, 'apakah anda yakin ingin membatalkan berita ini?')
        }else if(event.target.classList.contains('confirmated')){
            event.preventDefault();

            fetchGET(`{{ url('api/berita/acc') }}/${idBerita}`, 'apakah anda yakin ingin meng-acc berita ini?')
        }else if(event.target.classList.contains('deleted')){
            event.preventDefault();

            fetchGET(`{{ url('api/berita/delete') }}/${idBerita}`, 'apakah anda yakin ingin menghapus berita ini?')
        }
    });
  }catch(err){

  }

function fetchGET(url, message){
  const userSecret = "{{ $user['secret'] }}"; // Inject token from Blade to JS

  if(confirm(message)){
    fetch(url,{
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${userSecret}`,
      }
    }).then(response => response.json())
        .then(data => {
            if (data.message) {
              alert(data.message)
                // Hapus token dari localStorage
                // updateUIAfterLogout();
                window.location.href = `${data.redirect}${userSecret}`;
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error('Error:', error));
  }
}

function toggleSubMenuDinamis(event) {
        event.preventDefault();
        const parentLi = event.target.closest('li');
        parentLi.classList.toggle('show-sub-menu');
    }

    async function getKategori(){
        const userSecret = "{{ $user['secret'] }}"; // Inject token from Blade to JS

        try {
          const response = await fetch(`{{ url('/api/kategori') }}`, {
              method: 'GET', // atau 'POST', 'PUT', dll.
              headers: {
                  'Content-Type': 'application/json',
                  'Authorization': `Bearer ${userSecret}`  // Jika Anda perlu mengirimkan token
              }
          });
            const data = await response.json();

            const kategoriSubNav = document.getElementById('kategori-sub-nav');
            if(!kategoriSubNav){

            }else{
              data.forEach(kategori => {
                  const subLi = document.createElement('li');
                  const url = `{{ url('admin/kategori') }}/${kategori.nama_kategori}/${userSecret}`;
                  subLi.innerHTML = `<a href="${url}">
                          <i class="uil uil-arrow"></i>
                          <span class="link-name">${kategori.nama_kategori}</span>
                      </a>`;

                  kategoriSubNav.appendChild(subLi);
              });
            }
            
        } catch (error) {
            console.error('Error fetching kategori:', error);
        }
    }
    try{
      getKategori();
    }catch(err){}
    
    </script>
</body>
</html>