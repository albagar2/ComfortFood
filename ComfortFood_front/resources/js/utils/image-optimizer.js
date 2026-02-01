/**
 * Image Optimizer Utility for ComfortFood
 * Uses browser-image-compression to handle client-side optimization.
 */

window.ImageOptimizer = {
    // Quality presets
    PRESETS: {
        AVATAR: {
            maxSizeMB: 0.2,
            maxWidthOrHeight: 400,
            initialQuality: 0.8,
            fileType: 'image/webp'
        },
        BANNER: {
            maxSizeMB: 0.8,
            maxWidthOrHeight: 1200,
            initialQuality: 0.7,
            fileType: 'image/webp'
        },
        MENU_ORIGINAL: {
            maxSizeMB: 1,
            maxWidthOrHeight: 1200,
            initialQuality: 0.8,
            fileType: 'image/webp'
        },
        MENU_CARD: {
            maxSizeMB: 0.1,
            maxWidthOrHeight: 400,
            initialQuality: 0.6,
            fileType: 'image/webp'
        }
    },

    /**
     * Compress a single file with a given preset
     * @param {File} file 
     * @param {Object} preset 
     * @returns {Promise<File>}
     */
    async compress(file, presetName = 'AVATAR') {
        const options = this.PRESETS[presetName] || this.PRESETS.AVATAR;
        options.useWebWorker = true;

        try {
            const compressedBlob = await imageCompression(file, options);
            const fileName = file.name.replace(/\.[^/.]+$/, "") + ".webp";
            return new File([compressedBlob], fileName, { type: 'image/webp' });
        } catch (error) {
            console.error('Optimization failed:', error);
            return file; // Fallback to original
        }
    },

    /**
     * Special handler for Menu items that need both original and card versions
     * @param {File} file 
     * @returns {Promise<{original: File, card: File}>}
     */
    async processMenu(file) {
        const [original, card] = await Promise.all([
            this.compress(file, 'MENU_ORIGINAL'),
            this.compress(file, 'MENU_CARD')
        ]);

        return { original, card };
    }
};
