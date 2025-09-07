<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="p-6">
    <?php require_once '../app/views/admin/partials/back_button.php'; ?>
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Gestão de Mesas</h2>
            <p class="text-gray-600 mt-1">Configure as mesas e gere QR codes para o cardápio</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <a href="<?= URL_BASE ?>/admin/addmesa" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Mesa
            </a>
        </div>
    </div>

    <!-- Mesas Grid -->
    <?php if (empty($dados['mesas'])): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-chair text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Nenhuma mesa encontrada</h3>
            <p class="text-gray-500 mb-6">Comece adicionando mesas ao seu estabelecimento</p>
            <a href="<?= URL_BASE ?>/admin/addmesa" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200 inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Primeira Mesa
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($dados['mesas'] as $mesa): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Header da Mesa -->
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Mesa <?= htmlspecialchars($mesa->numero); ?></h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $mesa->status === 'livre' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <div class="w-1.5 h-1.5 rounded-full <?= $mesa->status === 'livre' ? 'bg-green-400' : 'bg-red-400' ?> mr-1.5"></div>
                                <?= ucfirst(htmlspecialchars($mesa->status)); ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- QR Code -->
                    <div class="p-4 text-center">
                        <?php if (!empty($mesa->qr_code) && file_exists('uploads/qrcodes/' . $mesa->qr_code)): ?>
                            <div class="mb-4">
                                <a href="<?= URL_BASE ?>/uploads/qrcodes/<?= htmlspecialchars($mesa->qr_code); ?>" target="_blank" class="inline-block">
                                    <img src="<?= URL_BASE ?>/uploads/qrcodes/<?= htmlspecialchars($mesa->qr_code); ?>" alt="QR Code Mesa <?= htmlspecialchars($mesa->numero); ?>" class="w-24 h-24 mx-auto border border-gray-200 rounded-lg">
                                </a>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">Clique para ver em tamanho real</p>
                        <?php else: ?>
                            <div class="w-24 h-24 mx-auto bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                                <i class="fas fa-qrcode text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">QR Code não disponível</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Ações -->
                    <div class="p-4 bg-gray-50 space-y-2">
                        <button
                            class="w-full copy-url-btn bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center"
                            data-url="<?= gerarUrlEstabelecimento(htmlspecialchars($dados['estabelecimento']->subdominio)); ?>/?mesa=<?= $mesa->id; ?>"
                            title="Copiar URL da Mesa"
                        >
                            <i class="fas fa-copy mr-2"></i>
                            <span>Copiar Link</span>
                        </button>
                        
                        <div class="flex space-x-2">
                            <a href="<?= URL_BASE ?>/admin/editmesa/<?= $mesa->id; ?>" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-center">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>
                            <form action="<?= URL_BASE ?>/admin/deletemesa/<?= $mesa->id; ?>" method="POST" class="flex-1" onsubmit="return confirm('Tem certeza que deseja excluir esta mesa?');">
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyButtons = document.querySelectorAll('.copy-url-btn');

    copyButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const urlToCopy = this.dataset.url;

            const textArea = document.createElement("textarea");
            textArea.value = urlToCopy;
            textArea.style.position = "fixed";
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.opacity = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess(this);
                }
            } catch (err) {
                console.error('Erro ao copiar a URL:', err);
                alert('Não foi possível copiar o link.');
            }
            document.body.removeChild(textArea);
        });
    });
    
    function showCopySuccess(buttonElement) {
        const originalContent = buttonElement.innerHTML;
        const originalTitle = buttonElement.title;

        buttonElement.innerHTML = `
            <i class="fas fa-check mr-2"></i>
            <span>Copiado!</span>
        `;
        buttonElement.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'text-gray-700');
        buttonElement.classList.add('bg-green-100', 'text-green-700');
        buttonElement.title = 'Copiado!';
        buttonElement.disabled = true;

        setTimeout(() => {
            buttonElement.innerHTML = originalContent;
            buttonElement.classList.remove('bg-green-100', 'text-green-700');
            buttonElement.classList.add('bg-gray-200', 'hover:bg-gray-300', 'text-gray-700');
            buttonElement.title = originalTitle;
            buttonElement.disabled = false;
        }, 2000);
    }
});
</script>

<?php require_once '../app/views/admin/partials/footer.php'; ?>

