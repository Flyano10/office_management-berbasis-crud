<style>
  .contact-fab { position: fixed; right: 18px; bottom: 18px; z-index: 1100; }
  .contact-fab__btn { width: 56px; height: 56px; border-radius: 50%; border: none; background: #25D366; color: #fff; box-shadow: 0 8px 24px rgba(0,0,0,.18); display: flex; align-items: center; justify-content: center; font-size: 22px; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, background .2s ease; }
  .contact-fab__btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,.22); }
  .contact-fab__btn:focus { outline: none; }
  .contact-fab__panel { position: absolute; right: 0; bottom: 70px; width: 280px; background: #fff; border: 1px solid rgba(0,0,0,.06); border-radius: 12px; box-shadow: 0 12px 32px rgba(0,0,0,.12); overflow: hidden; opacity: 0; transform: translateY(8px); pointer-events: none; transition: opacity .2s ease, transform .2s ease; }
  .contact-fab.open .contact-fab__panel { opacity: 1; transform: translateY(0); pointer-events: auto; }
  .contact-fab__header { background: linear-gradient(135deg, #2C6A8F 0%, #1E4A5F 100%); color: #fff; padding: 12px 14px; display: flex; align-items: center; gap: 10px; }
  .contact-fab__header i { font-size: 16px; }
  .contact-fab__header h6 { font-size: 14px; font-weight: 700; margin: 0; }
  .contact-fab__body { padding: 10px; background: #fff; }
  .contact-fab__list { list-style: none; padding: 0; margin: 0; display: grid; gap: 8px; }
  .contact-item { display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid #eef2f7; border-radius: 10px; text-decoration: none; color: #0f172a; transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease; }
  .contact-item:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.08); border-color: #e5e7eb; }
  .contact-item__icon { width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; color: #fff; font-size: 16px; }
  .contact-item__label { display: flex; flex-direction: column; }
  .contact-item__title { font-size: 13px; font-weight: 700; line-height: 1.15; }
  .contact-item__desc { font-size: 12px; color: #64748b; line-height: 1.2; }
  .bg-wa { background: #25D366; }
  .bg-phone { background: #2563EB; }
  .bg-mail { background: #F59E0B; }
  .bg-chat { background: #10B981; }
  @media (max-width: 480px) { .contact-fab__panel { width: calc(100vw - 36px); } }
</style>
<div class="contact-fab" id="contactFab" aria-live="polite">
  <button type="button" class="contact-fab__btn" id="contactFabToggle" aria-haspopup="true" aria-expanded="false" aria-controls="contactFabPanel" title="Hubungi kami">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="icon icon-20" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
    </svg>
  </button>
  <div class="contact-fab__panel" id="contactFabPanel" role="dialog" aria-label="Kontak">
    <div class="contact-fab__header">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="icon icon-20 me-1" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
      </svg>
      <h6>Butuh bantuan?</h6>
    </div>
    <div class="contact-fab__body">
      <ul class="contact-fab__list">
        @php
          $wa = config('app.contact_whatsapp', env('CONTACT_WHATSAPP', '6281234567890'));
          $tel = config('app.contact_phone', env('CONTACT_PHONE', '+62-21-1234567'));
          $mail = config('app.contact_email', env('CONTACT_EMAIL', 'support@example.com'));
          $waText = rawurlencode(config('app.contact_whatsapp_text', 'Halo, saya ingin bertanya mengenai layanan.'));
        @endphp
        <li>
          <a class="contact-item" target="_blank" rel="noopener" href="https://wa.me/{{ $wa }}?text={{ $waText }}">
            <span class="contact-item__icon bg-wa"><i class="fab fa-whatsapp"></i></span>
            <span class="contact-item__label">
              <span class="contact-item__title">WhatsApp</span>
              <span class="contact-item__desc">Chat langsung via WhatsApp</span>
            </span>
          </a>
        </li>
        <li>
          <a class="contact-item" href="tel:{{ preg_replace('/[^+0-9]/', '', $tel) }}">
            <span class="contact-item__icon bg-phone"><i class="fas fa-phone"></i></span>
            <span class="contact-item__label">
              <span class="contact-item__title">Telepon</span>
              <span class="contact-item__desc">{{ $tel }}</span>
            </span>
          </a>
        </li>
        <li>
          <a class="contact-item" href="mailto:{{ $mail }}">
            <span class="contact-item__icon bg-mail"><i class="fas fa-envelope"></i></span>
            <span class="contact-item__label">
              <span class="contact-item__title">Email</span>
              <span class="contact-item__desc">{{ $mail }}</span>
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
<script>
  (function(){
    const root = document.getElementById('contactFab');
    if(!root) return;
    const btn = document.getElementById('contactFabToggle');
    const panel = document.getElementById('contactFabPanel');
    const closePanel = () => { root.classList.remove('open'); btn.setAttribute('aria-expanded','false'); };
    const openPanel = () => { root.classList.add('open'); btn.setAttribute('aria-expanded','true'); };
    btn.addEventListener('click', (e)=>{ e.stopPropagation(); root.classList.contains('open') ? closePanel() : openPanel(); });
    document.addEventListener('click', (e)=>{ if(!root.contains(e.target)) closePanel(); });
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closePanel(); });
  })();
</script>