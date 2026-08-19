<!-- TEXT TO SPEECH (SUARA WANITA BAHASA INDONESIA - HIDDEN WIDGET) -->
<script src="https://code.responsivevoice.org/responsivevoice.js?key=FREE_KEY"></script>

<div id="tts-floating-widget" class="hidden">
    <div id="tts-status-text">Suara Wanita Bahasa Indonesia</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const synth = window.speechSynthesis;
    let currentAudio = null;
    let currentHighlightedEl = null;
    let cachedIndoVoice = null;

    function clearHighlight() {
        if (currentHighlightedEl) {
            currentHighlightedEl.classList.remove('ring-2', 'ring-emerald-500', 'ring-offset-2', 'bg-emerald-50/60', 'rounded-lg', 'transition-all');
            currentHighlightedEl = null;
        }
    }

    function setReadingState(isReading) {
        if (!isReading) {
            clearHighlight();
        }
    }

    window.stopTTS = function() {
        if (window.responsiveVoice && typeof responsiveVoice.isPlaying === 'function' && responsiveVoice.isPlaying()) {
            responsiveVoice.cancel();
        }
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;
        }
        if (synth) {
            synth.cancel();
        }
        setReadingState(false);
    };

    // Cari suara wanita Bahasa Indonesia terbaik di browser
    function loadIndonesianVoice() {
        if (!synth) return null;
        const voices = synth.getVoices();
        if (!voices || voices.length === 0) return null;

        // Prioritas 1: Suara Indonesia Wanita (Gadis, Wita, Female)
        let voice = voices.find(v => v.lang.toLowerCase().startsWith('id') && /gadis|wita|female|wanita|google/i.test(v.name));
        if (voice) return voice;

        // Prioritas 2: Suara bahasa Indonesia id-ID apapun
        voice = voices.find(v => v.lang.toLowerCase() === 'id-id' || v.lang.toLowerCase() === 'id_id' || v.lang.toLowerCase().startsWith('id'));
        if (voice) return voice;

        return null;
    }

    if (synth) {
        cachedIndoVoice = loadIndonesianVoice();
        if (synth.onvoiceschanged !== undefined) {
            synth.onvoiceschanged = function() {
                cachedIndoVoice = loadIndonesianVoice();
            };
        }
    }

    // Format singkatan istilah instansi pemerintah agar dibaca jelas dalam Bahasa Indonesia
    function formatTextForIndoSpeech(text) {
        return text.replace(/\s+/g, ' ').trim()
            .replace(/\bDKUPP\b/g, 'D K U P P')
            .replace(/\bUMKM\b/g, 'U M K M')
            .replace(/\bMPP\b/g, 'M P P')
            .replace(/\bSAKIP\b/g, 'S A K I P')
            .replace(/\bSP4N\b/g, 'S P 4 N')
            .replace(/\bDiskominfo\b/g, 'Diskominfo');
    }

    function speakWithWebSpeech(text, targetEl) {
        if (!synth) return;
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 0.92;
        utterance.pitch = 1.2; // Pitch suara wanita lembut

        const indoVoice = cachedIndoVoice || loadIndonesianVoice();
        if (indoVoice) {
            utterance.voice = indoVoice;
        }

        utterance.onstart = function() {
            setReadingState(true);
            if (targetEl && targetEl.nodeType === 1) {
                currentHighlightedEl = targetEl;
                targetEl.classList.add('ring-2', 'ring-emerald-500', 'ring-offset-2', 'bg-emerald-50/60', 'rounded-lg', 'transition-all');
            }
        };

        utterance.onend = utterance.onerror = function() {
            setReadingState(false);
        };

        synth.speak(utterance);
    }

    function speakText(rawText, targetEl) {
        if (!rawText || rawText.trim() === '') return;

        window.stopTTS();

        const formattedText = formatTextForIndoSpeech(rawText);
        if (formattedText.length < 2) return;

        // PRIORITAS 1: ResponsiveVoice "Indonesian Female" (Suara Wanita Asli Bahasa Indonesia)
        if (window.responsiveVoice && typeof responsiveVoice.speak === 'function') {
            try {
                responsiveVoice.speak(formattedText.substring(0, 300), "Indonesian Female", {
                    pitch: 1.05,
                    rate: 0.92,
                    onstart: function() {
                        setReadingState(true);
                        if (targetEl && targetEl.nodeType === 1) {
                            currentHighlightedEl = targetEl;
                            targetEl.classList.add('ring-2', 'ring-emerald-500', 'ring-offset-2', 'bg-emerald-50/60', 'rounded-lg', 'transition-all');
                        }
                    },
                    onend: function() {
                        setReadingState(false);
                    },
                    onerror: function() {
                        speakWithGoogleAudioFallback(formattedText, targetEl);
                    }
                });
                return;
            } catch (err) {
                // Fallback jika responsiveVoice gagal
            }
        }

        speakWithGoogleAudioFallback(formattedText, targetEl);
    }

    function speakWithGoogleAudioFallback(formattedText, targetEl) {
        const shortText = formattedText.substring(0, 200);
        const googleAudioUrl = `https://translate.google.com/translate_tts?ie=UTF-8&tl=id&client=tw-ob&q=${encodeURIComponent(shortText)}`;

        const audio = new Audio(googleAudioUrl);
        currentAudio = audio;

        audio.onplay = function() {
            setReadingState(true);
            if (targetEl && targetEl.nodeType === 1) {
                currentHighlightedEl = targetEl;
                targetEl.classList.add('ring-2', 'ring-emerald-500', 'ring-offset-2', 'bg-emerald-50/60', 'rounded-lg', 'transition-all');
            }
        };

        audio.onended = function() {
            setReadingState(false);
        };

        audio.onerror = function() {
            currentAudio = null;
            speakWithWebSpeech(formattedText, targetEl);
        };

        audio.play().catch(function() {
            currentAudio = null;
            speakWithWebSpeech(formattedText, targetEl);
        });
    }

    // Event listener global untuk setiap klik teks
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('input') || e.target.closest('textarea') || e.target.closest('select') || e.target.closest('a[target="_blank"]')) {
            return;
        }

        const textTarget = e.target.closest('h1, h2, h3, h4, h5, h6, p, li, button, a, td, th, blockquote, label, span, figcaption');
        
        if (textTarget) {
            const textContent = textTarget.innerText || textTarget.textContent;
            if (textContent && textContent.trim().length > 1) {
                speakText(textContent, textTarget);
            }
        }
    }, true);
});
</script>
