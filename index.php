<?php 
require_once 'auth.php';
require_once 'config.php';
$auth = authenticate();
$deviceModel = "SM-J710MN";
$theme = $_COOKIE['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="es" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $deviceModel; ?> - Admin M3U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3c8dbc;
            --secondary-color: #367fa9;
            --bg-color: #f4f4f4;
            --sidebar-bg: #222d32;
            --text-color: #333;
            --card-bg: #fff;
            --border-color: #ddd;
            --header-height: 50px;
        }

        [data-theme="dark"] {
            --primary-color: #2c3e50;
            --secondary-color: #1a252f;
            --bg-color: #121212;
            --sidebar-bg: #1e1e1e;
            --text-color: #f1f1f1;
            --card-bg: #2d2d2d;
            --border-color: #444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            height: var(--header-height);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .header nav {
            display: flex;
            gap: 15px;
        }

        .header nav a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .header nav a:hover {
            background-color: var(--secondary-color);
        }

        .container {
            display: flex;
            min-height: 100vh;
            padding-top: var(--header-height);
        }

        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: white;
            padding: 20px 0;
            height: calc(100vh - var(--header-height));
            position: fixed;
            overflow-y: auto;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            background-color: var(--bg-color);
        }

        .playlist-type-header {
            padding: 10px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .playlist-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .playlist-list li {
            padding: 8px 20px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .playlist-list li:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .view {
            display: none;
            padding: 20px;
        }

        .view.active {
            display: block;
        }

        .settings-section {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }

        .server-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .server-info-card {
            background-color: var(--card-bg);
            padding: 15px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 5px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            margin-bottom: 10px;
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Estilos para las vistas específicas */
        #welcome-view {
            text-align: center;
            padding: 40px 20px;
        }

        #channels-view table {
            width: 100%;
            border-collapse: collapse;
        }

        #channels-view th, #channels-view td {
            padding: 10px;
            border: 1px solid var(--border-color);
            text-align: left;
        }

        #channels-view th {
            background-color: var(--primary-color);
            color: white;
        }

        #channels-view tr:nth-child(even) {
            background-color: rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo"><?php echo $deviceModel; ?></div>
        <nav>
            <a href="#" onclick="showView('welcome')"><i class="fas fa-home"></i> Inicio</a>
            <a href="#" onclick="showView('channels')"><i class="fas fa-tv"></i> Canales</a>
            <a href="#" onclick="showView('status')"><i class="fas fa-signal"></i> Estado</a>
            <a href="#" onclick="showView('settings')"><i class="fas fa-cog"></i> Configuración</a>
            <a href="#" onclick="exportPlaylist()"><i class="fas fa-file-export"></i> Exportar</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </nav>
    </div>

    <div class="container">
        <div class="sidebar">
            <div class="playlist-type">
                <div class="playlist-type-header" onclick="togglePlaylistType('local')">
                    <span>Local</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <ul id="local-playlists" class="playlist-list"></ul>
            </div>

            <div class="playlist-type">
                <div class="playlist-type-header" onclick="togglePlaylistType('external')">
                    <span>Externas</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <ul id="external-playlists" class="playlist-list" style="display:none;"></ul>
            </div>

            <div style="padding: 20px;">
                <button class="btn btn-primary" onclick="showAddPlaylistModal()" style="width: 100%;">
                    <i class="fas fa-plus"></i> Agregar Playlist
                </button>
            </div>
        </div>

        <div class="main-content">
            <!-- Vista de Bienvenida -->
            <div id="welcome-view" class="view active">
                <h1>Bienvenido al Administrador M3U</h1>
                <p>Selecciona una lista de reproducción del menú lateral o agrega una nueva.</p>
            </div>

            <!-- Vista de Canales -->
            <div id="channels-view" class="view">
                <h2><i class="fas fa-tv"></i> Canales</h2>
                <div id="channels-container">
                    <p>Selecciona una lista de reproducción para ver sus canales.</p>
                </div>
            </div>

            <!-- Vista de Estado -->
            <div id="status-view" class="view">
                <h2><i class="fas fa-signal"></i> Estado de Canales</h2>
                <div id="status-container">
                    <p>Cargando estado de los canales...</p>
                </div>
            </div>

            <!-- Vista de Configuración -->
            <div id="settings-view" class="view">
                <h2><i class="fas fa-cog"></i> Configuración</h2>
                
                <div class="settings-section">
                    <h3>Apariencia</h3>
                    <div class="form-group">
                        <label for="theme-selector">Tema:</label>
                        <select id="theme-selector" class="form-control" onchange="changeTheme(this.value)">
                            <option value="light" <?php echo $theme == 'light' ? 'selected' : ''; ?>>Tema Claro</option>
                            <option value="dark" <?php echo $theme == 'dark' ? 'selected' : ''; ?>>Tema Oscuro</option>
                        </select>
                    </div>
                </div>

                <div class="settings-section">
                    <h3>Sincronización con Google Drive</h3>
                    <button id="enable-gdrive" class="btn btn-primary" onclick="initGoogleDrive()">
                        <i class="fab fa-google-drive"></i> Habilitar Sincronización
                    </button>
                    
                    <div id="gdrive-options" style="display:none; margin-top:15px;">
                        <h4>Playlists a sincronizar:</h4>
                        <div id="gdrive-playlists"></div>
                    </div>
                </div>

                <div class="settings-section">
                    <h3>Información del Servidor</h3>
                    <div class="server-info">
                        <div class="server-info-card">
                            <h4>Conexiones actuales</h4>
                            <p id="current-connections">Cargando...</p>
                        </div>
                        <div class="server-info-card">
                            <h4>Uso de RAM</h4>
                            <p id="ram-usage">Cargando...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para agregar playlist -->
            <div id="add-playlist-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()" style="float:right;cursor:pointer;font-size:20px;">&times;</span>
                    <h3>Agregar Nueva Playlist</h3>
                    
                    <div class="form-group">
                        <label for="playlist-type">Tipo de Playlist:</label>
                        <select id="playlist-type" class="form-control" onchange="changePlaylistForm()">
                            <option value="local">Local</option>
                            <option value="astra">Astra</option>
                            <option value="m3use">M3Use</option>
                            <option value="xtream">Xtream Codes</option>
                        </select>
                    </div>
                    
                    <div id="playlist-form-container">
                        <!-- Formulario dinámico aparecerá aquí -->
                    </div>
                    
                    <button class="btn btn-success" onclick="addPlaylist()" style="margin-top:15px;">
                        <i class="fas fa-save"></i> Guardar Playlist
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://apis.google.com/js/api.js"></script>
    <script>
        // Variables globales
        let playlists = [];
        let gdriveEnabled = false;
        let currentPlaylist = null;

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            loadPlaylists();
            loadServerInfo();
            loadChannelStatus();
            document.getElementById('theme-selector').value = '<?php echo $theme; ?>';
        });

        // Funciones básicas
        function showView(viewName) {
            document.querySelectorAll('.view').forEach(view => {
                view.classList.remove('active');
            });
            document.getElementById(viewName + '-view').classList.add('active');
        }

        function changeTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            document.cookie = `theme=${theme}; path=/; max-age=${60*60*24*30}`;
        }

        function togglePlaylistType(type) {
            const element = document.getElementById(`${type}-playlists`);
            const icon = element.previousElementSibling.querySelector('i');
            
            if (element.style.display === 'none') {
                element.style.display = 'block';
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                element.style.display = 'none';
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        }

        // Funciones de servidor
        function loadServerInfo() {
            fetch('server_info.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-connections').textContent = data.connections;
                    document.getElementById('ram-usage').textContent = data.memory;
                });
        }

        function loadChannelStatus() {
            fetch('get_channel_status.php')
                .then(response => response.json())
                .then(data => {
                    let html = '<table><tr><th>Lista</th><th>Canales</th><th>Activos</th><th>Inactivos</th></tr>';
                    
                    for (const [playlist, channels] of Object.entries(data)) {
                        const active = channels.filter(c => c.status === 'active').length;
                        const inactive = channels.length - active;
                        
                        html += `<tr>
                            <td>${playlist}</td>
                            <td>${channels.length}</td>
                            <td style="color: green;">${active}</td>
                            <td style="color: red;">${inactive}</td>
                        </tr>`;
                    }
                    
                    html += '</table>';
                    document.getElementById('status-container').innerHTML = html;
                });
        }

        // Funciones de Google Drive
        function initGoogleDrive() {
            gapi.load('client:auth2', () => {
                gapi.client.init({
                    apiKey: '<?php echo GOOGLE_DRIVE_API_KEY; ?>',
                    clientId: '<?php echo GOOGLE_DRIVE_CLIENT_ID; ?>',
                    scope: 'https://www.googleapis.com/auth/drive.file',
                    discoveryDocs: ['https://www.googleapis.com/discovery/v1/apis/drive/v3/rest']
                }).then(() => {
                    gdriveEnabled = true;
                    document.getElementById('enable-gdrive').textContent = 'Sincronización Habilitada';
                    document.getElementById('gdrive-options').style.display = 'block';
                });
            });
        }

        // Funciones de playlist
        function showAddPlaylistModal() {
            document.getElementById('add-playlist-modal').style.display = 'flex';
            changePlaylistForm();
        }

        function closeModal() {
            document.getElementById('add-playlist-modal').style.display = 'none';
        }

        function changePlaylistForm() {
            const type = document.getElementById('playlist-type').value;
            let html = '';
            
            if (type === 'local') {
                html = `
                    <div class="form-group">
                        <label for="playlist-name">Nombre:</label>
                        <input type="text" id="playlist-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="playlist-file">Archivo M3U:</label>
                        <input type="file" id="playlist-file" class="form-control" accept=".m3u,.m3u8" required>
                    </div>
                `;
            } else {
                html = `
                    <div class="form-group">
                        <label for="playlist-name">Nombre:</label>
                        <input type="text" id="playlist-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="playlist-url">URL:</label>
                        <input type="text" id="playlist-url" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="playlist-username">Usuario (opcional):</label>
                        <input type="text" id="playlist-username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="playlist-password">Contraseña (opcional):</label>
                        <input type="password" id="playlist-password" class="form-control">
                    </div>
                `;
            }
            
            document.getElementById('playlist-form-container').innerHTML = html;
        }

        function addPlaylist() {
            const type = document.getElementById('playlist-type').value;
            const name = document.getElementById('playlist-name').value;
            
            if (!name) {
                alert('El nombre es requerido');
                return;
            }
            
            if (type === 'local') {
                const file = document.getElementById('playlist-file').files[0];
                if (!file) {
                    alert('Selecciona un archivo M3U');
                    return;
                }
                
                const formData = new FormData();
                formData.append('file', file);
                formData.append('name', name);
                
                fetch('upload_playlist.php', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        alert('Playlist agregada correctamente');
                        closeModal();
                        loadPlaylists();
                    } else {
                        alert('Error al agregar la playlist');
                    }
                });
            } else {
                const url = document.getElementById('playlist-url').value;
                if (!url) {
                    alert('La URL es requerida');
                    return;
                }
                
                fetch('add_external_playlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        url: url,
                        type: type,
                        username: document.getElementById('playlist-username')?.value || '',
                        password: document.getElementById('playlist-password')?.value || ''
                    })
                }).then(response => {
                    if (response.ok) {
                        alert('Playlist externa agregada');
                        closeModal();
                        loadPlaylists();
                    } else {
                        response.json().then(data => {
                            alert(data.error || 'Error al agregar la playlist');
                        });
                    }
                });
            }
        }

        function loadPlaylists() {
            fetch('list_files.php')
                .then(response => response.json())
                .then(data => {
                    playlists = data;
                    renderPlaylists();
                });
        }

        function renderPlaylists() {
            const localList = document.getElementById('local-playlists');
            const externalList = document.getElementById('external-playlists');
            
            localList.innerHTML = '';
            externalList.innerHTML = '';
            
            playlists.forEach(playlist => {
                const li = document.createElement('li');
                li.textContent = playlist.name;
                li.onclick = () => {
                    currentPlaylist = playlist;
                    loadPlaylistChannels(playlist);
                    
                    // Resaltar la playlist seleccionada
                    document.querySelectorAll('.playlist-list li').forEach(item => {
                        item.style.backgroundColor = '';
                    });
                    li.style.backgroundColor = 'rgba(255,255,255,0.2)';
                };
                
                if (playlist.type === 'local') {
                    localList.appendChild(li);
                } else {
                    externalList.appendChild(li);
                }
            });
        }

        function loadPlaylistChannels(playlist) {
            if (playlist.type === 'local') {
                fetch('get_playlist_channels.php?file=' + encodeURIComponent(playlist.path.split('/').pop()))
                    .then(response => response.json())
                    .then(channels => {
                        displayChannels(channels);
                    });
            } else {
                // Para playlists externas, podrías hacer una llamada diferente
                fetch('get_playlist_channels.php?url=' + encodeURIComponent(playlist.url))
                    .then(response => response.json())
                    .then(channels => {
                        displayChannels(channels);
                    });
            }
        }

        function displayChannels(channels) {
            showView('channels');
            
            if (!channels || channels.length === 0) {
                document.getElementById('channels-container').innerHTML = '<p>No se encontraron canales en esta lista.</p>';
                return;
            }
            
            let html = '<table><tr><th>Nombre</th><th>URL</th><th>Lista</th></tr>';
            
            channels.forEach(channel => {
                html += `<tr>
                    <td>${channel.name}</td>
                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">${channel.url}</td>
                    <td>${currentPlaylist.name}</td>
                </tr>`;
            });
            
            html += '</table>';
            document.getElementById('channels-container').innerHTML = html;
        }

        function exportPlaylist() {
            if (!currentPlaylist) {
                alert('Selecciona una lista primero');
                return;
            }
            
            if (confirm(`¿Exportar la lista "${currentPlaylist.name}"?`)) {
                window.open('serve_m3u.php?file=' + encodeURIComponent(currentPlaylist.path.split('/').pop()), '_blank');
            }
        }
    </script>
</body>
</html>