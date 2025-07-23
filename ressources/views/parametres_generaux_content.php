<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($cardPGeneraux as $card): ?>
        <div class="relative bg-white rounded-3xl shadow-xl flex flex-col h-full transition-all duration-300 ease-in-out hover:shadow-2xl hover:-translate-y-2 border border-gray-100 p-4 min-h-[220px]">
            <!-- Badge flottant pour l'icône/image -->
                    <?php if (!empty($card['icon'])): ?>
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-10">
                <div class="bg-yellow-400 shadow-lg w-12 h-12 flex items-center justify-center rounded-full border-4 border-white overflow-hidden">
                    <img src="<?php echo htmlspecialchars($card['icon']); ?>" alt="icone" class="object-cover w-full h-full rounded-full">
                </div>
                    </div>
                    <?php endif; ?>
            <div class="flex flex-col h-full pt-8">
                <a href="<?php echo htmlspecialchars($card['link']); ?>" class="group flex-grow block text-center">
                    <h5 class="mb-1 text-base font-extrabold text-gray-900 group-hover:text-yellow-600 transition-colors">
                        <?php echo htmlspecialchars($card['title']); ?>
                    </h5>
                    <p class="text-xs text-gray-500 mb-2">
                        <?php echo htmlspecialchars($card['description']); ?>
                    </p>
                    <!-- Barre de progression factice -->
                    <div class="w-full h-2 bg-gray-200 rounded-full mb-2">
                        <div class="h-2 bg-yellow-400 rounded-full" style="width: 60%"></div>
                    </div>
                </a>
                <div class="mt-auto flex justify-center">
                    <a href="<?php echo htmlspecialchars($card['link']); ?>"
                        class="inline-flex items-center px-4 py-1.5 text-xs font-semibold text-white bg-black rounded-full hover:bg-yellow-custom hover:text-black transition-all duration-200 shadow group">
                        Accéder
                        <i class="ml-2 fas fa-chevron-right text-xs group-hover:text-yellow-custom"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>