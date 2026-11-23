<div class="modal-overlay" id="modal-logout" hidden>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-logout-title">
    <h2 class="modal-title" id="modal-logout-title">Keluar dari panel admin?</h2>
    <p class="modal-text">Kamu perlu masuk lagi untuk mengelola produk dan pesanan.</p>
    <form action="{{ route('logout') }}" method="POST" class="modal-actions">
      @csrf
      <button type="button" class="btn-batal" data-close-logout>Batal</button>
      <button type="submit" class="btn-ya">Ya, keluar</button>
    </form>
  </div>
</div>
