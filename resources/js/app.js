import './bootstrap';

window.beritaEditor = ({ wire, draftKey }) => ({
    mode: 'write',
    isDragging: false,
    title: wire.entangle('judul'),
    category: wire.entangle('kategori_id'),
    content: wire.entangle('konten_html'),
    status: wire.entangle('status_publikasi'),
    saveTimer: null,
    lastSavedAt: null,
    init() {
        this.restoreDraft();

        ['title', 'category', 'content', 'status'].forEach((prop) => {
            this.$watch(prop, () => this.queuePersist());
        });
    },
    get plainText() {
        return this.content
            .replace(/<style[^>]*>[\s\S]*?<\/style>/gi, ' ')
            .replace(/<script[^>]*>[\s\S]*?<\/script>/gi, ' ')
            .replace(/<[^>]+>/g, ' ')
            .replace(/&nbsp;/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    },
    get wordCount() {
        if (! this.plainText) {
            return 0;
        }

        return this.plainText.split(/\s+/).length;
    },
    get readingMinutes() {
        if (this.wordCount === 0) {
            return 0;
        }

        return Math.max(1, Math.ceil(this.wordCount / 200));
    },
    get savedLabel() {
        if (! this.lastSavedAt) {
            return 'Belum ada autosave';
        }

        return 'Autosave ' + new Date(this.lastSavedAt).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
        });
    },
    queuePersist() {
        clearTimeout(this.saveTimer);
        this.saveTimer = window.setTimeout(() => this.persistDraft(), 500);
    },
    persistDraft() {
        const payload = {
            title: this.title,
            category: this.category,
            content: this.content,
            status: this.status,
            savedAt: Date.now(),
        };

        window.localStorage.setItem(draftKey, JSON.stringify(payload));
        this.lastSavedAt = payload.savedAt;
    },
    restoreDraft() {
        const raw = window.localStorage.getItem(draftKey);

        if (! raw) {
            return;
        }

        try {
            const draft = JSON.parse(raw);
            this.lastSavedAt = draft.savedAt ?? null;

            if (! this.title && ! this.content) {
                this.title = draft.title ?? '';
                this.category = draft.category ?? '';
                this.content = draft.content ?? '';
                this.status = draft.status ?? 'draft';
            }
        } catch {
            window.localStorage.removeItem(draftKey);
        }
    },
    clearSavedDraft() {
        window.localStorage.removeItem(draftKey);
        this.lastSavedAt = null;
    },
    focusEditor() {
        this.$nextTick(() => this.$refs.editor?.focus());
    },
    handleDroppedFiles(event, refName = 'inlineImageInput') {
        const files = Array.from(event.dataTransfer?.files ?? []).filter((file) => file.type.startsWith('image/'));

        this.isDragging = false;

        if (files.length === 0) {
            return;
        }

        const input = this.$refs[refName];

        if (! input) {
            return;
        }

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(files[0]);
        input.files = dataTransfer.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    },
    insert(before, after = '', fallback = '') {
        const input = this.$refs.editor;

        if (! input) {
            return;
        }

        const start = input.selectionStart ?? input.value.length;
        const end = input.selectionEnd ?? input.value.length;
        const selected = input.value.slice(start, end) || fallback;
        const replacement = before + selected + after;

        input.setRangeText(replacement, start, end, 'end');
        this.content = input.value;
        this.focusEditor();
    },
    block(tag, fallback = 'Teks judul') {
        this.insert(`<${tag}>`, `</${tag}>`, fallback);
    },
    paragraph() {
        this.insert('<p>', '</p>', 'Tulis paragraf di sini');
    },
    bulletList() {
        this.insert('<ul>\n    <li>', '</li>\n</ul>', 'Poin penting');
    },
    numberedList() {
        this.insert('<ol>\n    <li>', '</li>\n</ol>', 'Langkah penting');
    },
    quote() {
        this.insert('<blockquote>', '</blockquote>', 'Kutipan atau pernyataan penting');
    },
    link() {
        const url = window.prompt('Masukkan URL tautan');

        if (! url) {
            return;
        }

        this.insert(`<a href="${url}" target="_blank" rel="noopener">`, '</a>', 'Teks tautan');
    },
    imageFromUrl() {
        const url = window.prompt('Masukkan URL gambar');

        if (! url) {
            return;
        }

        this.insertUploadedImage(url);
    },
    insertUploadedImage(url) {
        this.insert(`<figure><img src="${url}" alt="Gambar berita" /><figcaption>`, '</figcaption></figure>', 'Keterangan gambar');
    },
    clearFormat() {
        this.content = '';
        this.focusEditor();
    },
    templateLead() {
        this.content = `<p><strong>Lead:</strong> Ringkas inti berita dalam satu sampai dua kalimat.</p>
<h2>Latar Belakang</h2>
<p>Jelaskan konteks berita.</p>
<h2>Poin Utama</h2>
<ul>
    <li>Masukkan poin penting pertama</li>
    <li>Masukkan poin penting kedua</li>
</ul>
<h2>Penutup</h2>
<p>Tutup dengan informasi lanjutan atau ajakan.</p>`;
        this.mode = 'write';
        this.focusEditor();
    },
});

document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => entry.target.classList.add('is-visible'), Number(delay));
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));

    // Re-observe after Livewire navigations
    document.addEventListener('livewire:navigated', () => {
        document.querySelectorAll('[data-animate]:not(.is-visible)').forEach(el => observer.observe(el));
    });
});
