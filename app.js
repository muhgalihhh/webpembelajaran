// LMS Psikologi Pendidikan - SPA sederhana berbasis JavaScript murni

// ----------------------------
// Utilitas
// ----------------------------
const $ = (sel, el = document) => el.querySelector(sel);
const $$ = (sel, el = document) => Array.from(el.querySelectorAll(sel));

const storageKey = 'lms_pp_state_v1';

function loadState() {
  try {
    const raw = localStorage.getItem(storageKey);
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

function saveState(state) {
  localStorage.setItem(storageKey, JSON.stringify(state));
}

function formatPercent(n) {
  return `${Math.round(n * 100)}%`;
}

// ----------------------------
// Data mata kuliah & struktur
// ----------------------------
const sessions = Array.from({ length: 16 }, (_, i) => {
  const num = i + 1;
  const isUTS = num === 8;
  const isUAS = num === 16;
  return {
    id: `s${num}`,
    number: num,
    title: isUTS ? 'UTS' : isUAS ? 'UAS' : `Pertemuan ${num}`,
    subtitle: isUTS ? 'Ujian Tengah Semester' : isUAS ? 'Ujian Akhir Semester' : 'Materi, Kuis/Simulasi, Evaluasi Formatif',
    isExam: isUTS || isUAS,
    material: {
      type: 'html',
      content: isUTS || isUAS
        ? `<p>Ujian ${isUTS ? 'Tengah' : 'Akhir'} Semester. Bacalah instruksi dengan cermat sebelum memulai.</p>`
        : `<p>Topik: Psikologi Pendidikan - Sesi ${num}. Pelajari konsep inti, contoh kasus, dan implikasi pedagogis.</p>`
    },
    quiz: isUTS || isUAS ? null : {
      type: 'mcq',
      questions: [
        {
          id: 'q1',
          text: 'Apa fokus utama psikologi pendidikan?',
          choices: ['Perilaku dalam konteks belajar', 'Struktur otak', 'Ekonomi pasar', 'Sejarah kuno'],
          answerIndex: 0
        },
        {
          id: 'q2',
          text: 'Motivasi intrinsik adalah...',
          choices: ['Dorongan dari luar', 'Dorongan dari dalam diri', 'Hadiah materi', 'Tekanan sosial'],
          answerIndex: 1
        }
      ]
    },
    evaluation: isUTS || isUAS ? null : {
      type: 'reflect',
      prompts: [
        'Sebutkan dua hal baru yang Anda pelajari.',
        'Bagaimana Anda akan menerapkan konsep ini di kelas?'
      ]
    },
    exam: isUTS || isUAS ? {
      durationMinutes: 30,
      questions: [
        { id: 'e1', text: 'Jelaskan perbedaan motivasi intrinsik dan ekstrinsik.', type: 'essay' },
        { id: 'e2', text: 'Buat rancangan pembelajaran singkat yang menerapkan teori belajar sosial.', type: 'essay' }
      ]
    } : null
  };
});

const defaultState = {
  theme: 'dark',
  progress: {}, // { s1: { materialDone, quizScore, evaluationDone, examScore } }
};

let state = loadState() || defaultState;

// ----------------------------
// Tema
// ----------------------------
function applyTheme(theme) {
  const root = document.documentElement;
  if (theme === 'light') root.classList.add('light');
  else root.classList.remove('light');
}

function toggleTheme() {
  state.theme = state.theme === 'light' ? 'dark' : 'light';
  saveState(state);
  applyTheme(state.theme);
}

// ----------------------------
// Router sederhana (#/route)
// ----------------------------
const routes = {
  '/dashboard': renderDashboard,
  '/session': renderSession,
};

function parseHash() {
  const hash = location.hash || '#/dashboard';
  const [path, param] = hash.slice(1).split('?');
  const url = new URLSearchParams(param || '');
  return { path: `/${path.replace(/^\//, '')}`, params: url };
}

function navigate(path) {
  if (location.hash !== `#${path}`) location.hash = `#${path}`;
  else handleRoute();
}

function handleRoute() {
  const { path, params } = parseHash();
  const view = routes[path] || renderDashboard;
  view(params);
  highlightActiveLink(path, params);
}

function highlightActiveLink(path, params) {
  $$('#sidebar .nav a, #sidebar .session-link').forEach(a => a.classList.remove('active'));
  if (path === '/dashboard') {
    const link = $('#sidebar .nav a[href="#/dashboard"]');
    if (link) link.classList.add('active');
  }
  if (path === '/session') {
    const s = params.get('id');
    const link = $(`#sidebar .session-link[data-id="${s}"]`);
    if (link) link.classList.add('active');
  }
}

// ----------------------------
// Sidebar links
// ----------------------------
function renderSidebar() {
  const container = $('#session-links');
  container.innerHTML = sessions.map(s => {
    const p = state.progress[s.id] || {};
    const status = s.isExam
      ? (p.examScore != null ? 'Selesai' : 'Belum')
      : (p.materialDone && p.quizScore != null && p.evaluationDone ? 'Selesai' : 'Progres');
    return `
      <a class="session-link" data-id="${s.id}" href="#/session?id=${s.id}">
        <span class="num">${s.number.toString().padStart(2, '0')}</span>
        <div>
          <div>${s.title}</div>
          <div class="section-subtitle">${s.subtitle}</div>
        </div>
        <span class="status">${status}</span>
      </a>
    `;
  }).join('');
}

// ----------------------------
// Progres global
// ----------------------------
function computeGlobalProgress() {
  const total = sessions.length;
  const done = sessions.filter(s => {
    const p = state.progress[s.id] || {};
    if (s.isExam) return p.examScore != null;
    return p.materialDone && p.quizScore != null && p.evaluationDone;
  }).length;
  return done / total;
}

function renderGlobalProgress() {
  const ratio = computeGlobalProgress();
  $('#global-progress-bar').style.width = formatPercent(ratio);
}

// ----------------------------
// Dasbor
// ----------------------------
function renderDashboard() {
  $('#page-title').textContent = 'Dasbor';
  const ratio = computeGlobalProgress();
  const upcoming = sessions.find(s => {
    const p = state.progress[s.id] || {};
    if (s.isExam) return p.examScore == null;
    return !(p.materialDone && p.quizScore != null && p.evaluationDone);
  });

  $('#app').innerHTML = `
    <div class="grid cols-3">
      <div class="card stat">
        <div class="label">Progres Keseluruhan</div>
        <div class="value">${formatPercent(ratio)}</div>
      </div>
      <div class="card stat">
        <div class="label">Pertemuan Selesai</div>
        <div class="value">${Math.round(ratio * sessions.length)} / ${sessions.length}</div>
      </div>
      <div class="card stat">
        <div class="label">Pertemuan Berikutnya</div>
        <div class="value">${upcoming ? upcoming.title : 'Semua selesai'}</div>
        ${upcoming ? `<div class="footer-actions" style="margin-top:6px;"><a class="btn" href=\"#/session?id=${upcoming.id}\">Lanjutkan</a></div>` : ''}
      </div>
    </div>

    <div class="grid" style="margin-top:16px;">
      <div class="card">
        <div class="section-title">Semua Pertemuan</div>
        <div class="list">
          ${sessions.map(s => {
            const p = state.progress[s.id] || {};
            const badge = s.isExam ? 'Ujian' : 'Reguler';
            const status = s.isExam ? (p.examScore != null ? 'Selesai' : 'Belum') : (p.materialDone && p.quizScore != null && p.evaluationDone ? 'Selesai' : 'Progres');
            return `
              <div class="session-card">
                <div class="badge">${badge}</div>
                <div>
                  <div><strong>${s.title}</strong> — <span class="section-subtitle">${s.subtitle}</span></div>
                </div>
                <div class="footer-actions">
                  <a class="btn" href="#/session?id=${s.id}">Buka</a>
                  <span class="section-subtitle">${status}</span>
                </div>
              </div>
            `;
          }).join('')}
        </div>
      </div>
    </div>
  `;

  renderGlobalProgress();
}

// ----------------------------
// Render pertemuan
// ----------------------------
function renderSession(params) {
  const id = params.get('id') || 's1';
  const s = sessions.find(x => x.id === id) || sessions[0];
  $('#page-title').textContent = s.title;
  const p = state.progress[s.id] || {};

  if (s.isExam) {
    renderExam(s, p);
    return;
  }

  $('#app').innerHTML = `
    <div class="grid cols-2">
      <div class="card material">
        <h3>Bahan Ajar</h3>
        <div class="section-subtitle">Baca materi berikut.</div>
        <div>${s.material.content}</div>
        <div class="footer-actions">
          <button class="btn" id="markMaterial">Tandai Sudah Dibaca</button>
        </div>
      </div>

      <div class="grid" style="gap:16px;">
        <div class="card quiz">
          <h3>Kuis / Simulasi</h3>
          <div class="section-subtitle">Jawab pertanyaan berikut.</div>
          <div id="quiz"></div>
          <div class="footer-actions">
            <button class="btn" id="submitQuiz">Kumpulkan Kuis</button>
          </div>
        </div>

        <div class="card evaluation">
          <h3>Evaluasi Formatif</h3>
          <div class="section-subtitle">Refleksi pembelajaran.</div>
          <div id="evaluation"></div>
          <div class="footer-actions">
            <button class="btn" id="submitEval">Simpan Evaluasi</button>
          </div>
        </div>
      </div>
    </div>
  `;

  // Material
  $('#markMaterial').addEventListener('click', () => {
    state.progress[s.id] = { ...(state.progress[s.id] || {}), materialDone: true };
    saveState(state); renderSidebar(); renderGlobalProgress();
  });

  // Quiz
  renderQuiz($('#quiz'), s, p);
  $('#submitQuiz').addEventListener('click', () => submitQuiz(s));

  // Evaluation
  renderEvaluation($('#evaluation'), s, p);
  $('#submitEval').addEventListener('click', () => submitEvaluation(s));
}

function renderQuiz(container, session, progress) {
  if (!session.quiz) { container.innerHTML = '<div class="section-subtitle">Tidak ada kuis.</div>'; return; }
  container.innerHTML = session.quiz.questions.map((q, idx) => `
    <div class="question" data-id="${q.id}">
      <div class="q">${idx + 1}. ${q.text}</div>
      <div class="choices">
        ${q.choices.map((c, i) => `
          <label class="choice">
            <input type="radio" name="${q.id}" value="${i}"> ${c}
          </label>
        `).join('')}
      </div>
    </div>
  `).join('');

  if (progress.quizScore != null) {
    const scoreText = `Skor terakhir: ${Math.round(progress.quizScore * 100)}%`;
    const info = document.createElement('div');
    info.className = 'section-subtitle';
    info.textContent = scoreText;
    container.prepend(info);
  }
}

function submitQuiz(session) {
  const answers = {};
  session.quiz.questions.forEach(q => {
    const checked = $(`input[name="${q.id}"]:checked`);
    answers[q.id] = checked ? Number(checked.value) : -1;
  });
  const correct = session.quiz.questions.filter(q => answers[q.id] === q.answerIndex).length;
  const score = correct / session.quiz.questions.length;
  state.progress[session.id] = { ...(state.progress[session.id] || {}), quizScore: score };
  saveState(state);
  alert(`Kuis disimpan. Skor: ${Math.round(score * 100)}%`);
  renderSidebar(); renderGlobalProgress();
}

function renderEvaluation(container, session, progress) {
  if (!session.evaluation) { container.innerHTML = '<div class="section-subtitle">Tidak ada evaluasi.</div>'; return; }
  container.innerHTML = session.evaluation.prompts.map((p, i) => `
    <label>
      <div class="section-subtitle" style="margin:6px 0;">${i + 1}. ${p}</div>
      <textarea class="input" id="eval_${i}" placeholder="Tulis jawaban Anda..."></textarea>
    </label>
  `).join('');

  if (progress.evaluationDone) {
    const info = document.createElement('div');
    info.className = 'section-subtitle';
    info.textContent = 'Evaluasi telah disimpan.';
    container.prepend(info);
  }
}

function submitEvaluation(session) {
  const texts = session.evaluation.prompts.map((_, i) => $(`#eval_${i}`).value.trim());
  const done = texts.every(t => t.length >= 5);
  if (!done) { alert('Mohon isi evaluasi dengan minimal 5 karakter per jawaban.'); return; }
  state.progress[session.id] = { ...(state.progress[session.id] || {}), evaluationDone: true };
  saveState(state);
  alert('Evaluasi tersimpan.');
  renderSidebar(); renderGlobalProgress();
}

// ----------------------------
// Ujian (UTS & UAS)
// ----------------------------
let examTimer = null;
function renderExam(session, progress) {
  $('#app').innerHTML = `
    <div class="card">
      <div class="section-title">${session.title} — ${session.subtitle}</div>
      <div class="section-subtitle">Durasi: <span class="timer" id="timer"></span></div>
      <div class="list" id="exam-questions" style="margin-top:12px;"></div>
      <div class="footer-actions">
        <button class="btn" id="submitExam">Kumpulkan Ujian</button>
      </div>
    </div>
  `;

  const duration = session.exam.durationMinutes * 60; // seconds
  startTimer(duration, $('#timer'), () => {
    finishExam(session);
  });

  const list = $('#exam-questions');
  list.innerHTML = session.exam.questions.map((q, i) => `
    <div class="question">
      <div class="q">${i + 1}. ${q.text}</div>
      ${q.type === 'essay' ? `<textarea class="input" id="ex_${q.id}" placeholder="Jawaban esai..."></textarea>` : ''}
    </div>
  `).join('');

  $('#submitExam').addEventListener('click', () => finishExam(session));

  if (progress.examScore != null) {
    const info = document.createElement('div');
    info.className = 'section-subtitle';
    info.textContent = `Ujian telah dikumpulkan. Skor: ${Math.round(progress.examScore * 100)}%`;
    $('#app .card').prepend(info);
  }
}

function startTimer(totalSeconds, el, onEnd) {
  let remaining = totalSeconds;
  const tick = () => {
    const m = Math.floor(remaining / 60).toString().padStart(2, '0');
    const s = Math.floor(remaining % 60).toString().padStart(2, '0');
    el.textContent = `${m}:${s}`;
    remaining -= 1;
    if (remaining < 0) {
      clearInterval(examTimer); examTimer = null; onEnd && onEnd();
    }
  };
  tick();
  if (examTimer) clearInterval(examTimer);
  examTimer = setInterval(tick, 1000);
}

function finishExam(session) {
  // Penilaian sederhana berbasis panjang jawaban
  const answers = session.exam.questions.map(q => ($(`#ex_${q.id}`)?.value || '').trim());
  const lengths = answers.map(t => Math.min(t.length, 400));
  const total = lengths.reduce((a, b) => a + b, 0);
  const max = session.exam.questions.length * 400;
  const score = max ? total / max : 0;
  state.progress[session.id] = { ...(state.progress[session.id] || {}), examScore: score };
  saveState(state);
  if (examTimer) { clearInterval(examTimer); examTimer = null; }
  alert(`Ujian dikumpulkan. Skor: ${Math.round(score * 100)}%`);
  renderSidebar(); renderGlobalProgress();
}

// ----------------------------
// Init
// ----------------------------
function init() {
  applyTheme(state.theme || 'dark');
  $('#themeToggle').addEventListener('click', toggleTheme);
  $('#sidebarToggle').addEventListener('click', () => $('#sidebar').classList.toggle('open'));

  renderSidebar();
  window.addEventListener('hashchange', handleRoute);
  handleRoute();
}

document.addEventListener('DOMContentLoaded', init);

