// resources/js/app.js
// import CSS agar vite bundling
import '../css/app.css'
import axios from 'axios'

// definisikan data awal dari server lewat window.__... (jika tidak ada, fallback ke array/obj)
const SURAT = window.__SURAT_DATA__ ?? []
const KLAS = window.__KLAS__ ?? []
const STATS = window.__STATS__ ?? { incoming:0, outgoing:0, klasifikasi:0, users:1 }

// buat app sebagai global function yang dipakai x-data="app()"
window.app = function(){
  return {
    // data awal (safe defaults)
    data: Array.isArray(SURAT) ? SURAT : [],
    klas: Array.isArray(KLAS) ? KLAS : [],
    stats: (typeof STATS === 'object' && STATS !== null) ? STATS : { incoming:0, outgoing:0, klasifikasi:0, users:1 },

    // modal for surat
    modalOpen: false,
    modalMode: 'create',
    selected: null,
    form: { id:null, no_surat:'', tanggal:'', pengirim:'', perihal:'', klasifikasi_id:'' },
    errors: {},
    selectedFile: null,

    // modal for klasifikasi
    klasModalOpen: false,
    klasModalMode: 'create',
    klasForm: { id:null, nama:'' },
    klasErrors: {},

    page: 1,
    perPage: 10,
    pages: 1,
    total: 0,
    filterKlas: '',
    filterDate: '',

    // loading / saving state
    isLoading: false,
    isSaving: false,

    // toast
    toast: { show: false, message: '', type: 'info' },
    showToast(message, type='info', timeout=3000){
      this.toast.message = message
      this.toast.type = type
      this.toast.show = true
      setTimeout(()=> this.toast.show = false, timeout)
    },

    // init called via x-init
    init(){
      this.load()
      this.loadKlas()
    },

    async load(){
      this.isLoading = true
      try {
        const res = await axios.get('/api/surat', { params: { page: this.page, per_page: this.perPage, q: this.query || undefined, klasifikasi_id: this.filterKlas || undefined, tanggal: this.filterDate || undefined } })
        const d = res.data
        // Laravel paginator structure
        this.data = d.data || []
        this.page = d.current_page || 1
        this.pages = d.last_page || 1
        this.total = d.total || this.data.length
        this.stats.incoming = this.total
      } catch(e){
        console.error('load error', e)
        this.showToast('Gagal memuat data', 'error')
      } finally {
        this.isLoading = false
      }
    },

    async loadKlas(){
      try {
        const res = await axios.get('/api/klasifikasi')
        this.klas = res.data || []
        this.stats.klasifikasi = this.klas.length
      } catch(e){
        console.error('load klas error', e)
      }
    },

    // actions
    openCreate(){ this.modalMode = 'create'; this.form = { id:null, no_surat:'', tanggal:'', pengirim:'', perihal:'', klasifikasi_id:'' }; this.errors = {}; this.selectedFile = null; this.modalOpen = true },
    openKlas(){ this.klasModalOpen = true; this.loadKlas() },
    openCreateKlas(){ this.klasModalMode = 'create'; this.klasForm = { id:null, nama:'' }; this.klasErrors = {} },
    editKlas(k){ this.klasModalMode = 'edit'; this.klasForm = { id: k.id, nama: k.nama }; this.klasErrors = {} },
    closeKlasModal(){ this.klasModalOpen = false; this.klasForm = { id:null, nama:'' } },
    view(s){ this.selected = s },
    edit(s){ this.modalMode = 'edit'; this.form = { ...s, klasifikasi_id: s.klasifikasi_id ?? s.klasifikasi?.id ?? '' }; this.errors = {}; this.selectedFile = null; this.modalOpen = true },
    async remove(s){ if(!confirm('Hapus surat ini?')) return; this.isLoading = true; try{ await axios.delete('/api/surat/'+s.id); this.showToast('Surat dihapus', 'success'); this.load() }catch(e){ console.error(e); this.showToast('Gagal hapus surat', 'error') } finally{ this.isLoading = false } },

    onFile(e){ const file = e.target.files?.[0]; if(file){ this.selectedFile = file; console.log('selected file', file.name) } },

    async save(){
      this.isSaving = true
      try{
        this.errors = {}
        const payload = new FormData()
        payload.append('no_surat', this.form.no_surat || '')
        payload.append('tanggal', this.form.tanggal || '')
        payload.append('pengirim', this.form.pengirim || '')
        payload.append('perihal', this.form.perihal || '')
        payload.append('klasifikasi_id', this.form.klasifikasi_id || '')
        if(this.selectedFile) payload.append('file', this.selectedFile)

        const config = { headers: { 'Content-Type': 'multipart/form-data' } }

        if(this.modalMode === 'create'){
          await axios.post('/api/surat', payload, config)
          this.showToast('Berhasil menambah surat', 'success')
        } else {
          await axios.post('/api/surat/'+this.form.id, payload, config)
          this.showToast('Perubahan tersimpan', 'success')
        }
        this.closeModal(); this.load()
      }catch(e){
        if (e.response && e.response.status === 422) {
          this.errors = e.response.data.errors || {}
        } else {
          console.error('save error', e)
          this.showToast('Terjadi kesalahan saat menyimpan', 'error')
        }
      } finally {
        this.isSaving = false
      }
    },

    closeModal(){ this.modalOpen = false; this.form = { id:null, no_surat:'', tanggal:'', pengirim:'', perihal:'', klasifikasi_id:'' } },

    async exportPdf(){
      try{
        window.location.href = '/api/export/pdf?' + new URLSearchParams({
          q: this.query || '',
          klasifikasi_id: this.filterKlas || '',
        }).toString()
        this.showToast('File PDF sedang diunduh...', 'success')
      }catch(e){
        console.error('export error', e)
        this.showToast('Gagal export PDF', 'error')
      }
    },

    async exportCsv(){
      try{
        window.location.href = '/api/export/csv?' + new URLSearchParams({
          q: this.query || '',
          klasifikasi_id: this.filterKlas || '',
        }).toString()
        this.showToast('File CSV sedang diunduh...', 'success')
      }catch(e){
        console.error('export error', e)
        this.showToast('Gagal export CSV', 'error')
      }
    },

    async saveKlas(){
      this.isSaving = true
      try{
        this.klasErrors = {}
        const payload = { nama: this.klasForm.nama || '' }

        if(this.klasModalMode === 'create'){
          await axios.post('/api/klasifikasi', payload)
          this.showToast('Klasifikasi berhasil ditambahkan', 'success')
        } else {
          await axios.put('/api/klasifikasi/'+this.klasForm.id, payload)
          this.showToast('Klasifikasi berhasil diperbarui', 'success')
        }
        this.closeKlasModal(); this.loadKlas()
      }catch(e){
        if (e.response && e.response.status === 422) {
          this.klasErrors = e.response.data.errors || {}
        } else {
          console.error('save klas error', e)
          this.showToast('Terjadi kesalahan saat menyimpan', 'error')
        }
      } finally {
        this.isSaving = false
      }
    },

    async removeKlas(k){
      if(!confirm('Hapus klasifikasi "'+k.nama+'"?')) return
      this.isLoading = true
      try{
        await axios.delete('/api/klasifikasi/'+k.id)
        this.showToast('Klasifikasi dihapus', 'success')
        this.loadKlas()
      }catch(e){
        console.error(e)
        this.showToast('Gagal hapus klasifikasi', 'error')
      } finally {
        this.isLoading = false
      }
    }
  }
}

// import Alpine dan start - pastikan window.app sudah didefinisikan ketika Alpine start
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()
