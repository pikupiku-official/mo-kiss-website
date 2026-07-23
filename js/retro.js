/**
 * モーキス公式サイト - レトロギミック ＆ BGMスクリプト
 */
(function() {
    // 1. 右クリック禁止ギミック
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert('当サイトは右クリック禁止です！');
    });

    // 2. Web Audio API によるレトロBGM
    let audioCtx = null;
    let bgmInterval = null;

    // 終末感のある哀愁漂う8bitアルペジオ (Am -> Dm -> G -> C)
    const chordAm = [220.00, 261.63, 329.63, 440.00]; // A3, C4, E4, A4
    const chordDm = [146.83, 174.61, 220.00, 293.66]; // D3, F3, A3, D4
    const chordG  = [196.00, 246.94, 293.66, 392.00]; // G3, B3, D4, G4
    const chordC  = [130.81, 164.81, 196.00, 261.63]; // C3, E3, G3, C4

    const sequence = [
        ...chordAm, ...chordAm,
        ...chordDm, ...chordDm,
        ...chordG,  ...chordG,
        ...chordC,  ...chordC
    ];

    let step = 0;
    const stepDuration = 0.20; // 200ms

    function startBgm() {
        if (window.top.bgmPlaying) return;
        
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }

        window.top.bgmPlaying = true;
        
        let nextPlayTime = audioCtx.currentTime;
        
        function scheduler() {
            while (nextPlayTime < audioCtx.currentTime + 0.1) {
                scheduleNote(step, nextPlayTime);
                nextPlayTime += stepDuration;
                step = (step + 1) % sequence.length;
            }
            if (window.top.bgmPlaying) {
                bgmInterval = setTimeout(scheduler, 25);
            }
        }
        
        scheduler();
        updateBgmButtons(true);
    }

    function scheduleNote(stepIndex, time) {
        if (!audioCtx) return;
        const freq = sequence[stepIndex];
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        
        osc.type = 'triangle';
        osc.frequency.setValueAtTime(freq, time);
        
        // 昔のMIDI/PSG音源っぽいアタックとデケイ
        gain.gain.setValueAtTime(0.08, time);
        gain.gain.exponentialRampToValueAtTime(0.001, time + stepDuration * 1.8);
        
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        
        osc.start(time);
        osc.stop(time + stepDuration * 1.9);
    }

    function stopBgm() {
        if (!window.top.bgmPlaying) return;
        window.top.bgmPlaying = false;
        if (bgmInterval) {
            clearTimeout(bgmInterval);
            bgmInterval = null;
        }
        if (audioCtx && audioCtx.state === 'running') {
            audioCtx.suspend();
        }
        updateBgmButtons(false);
    }

    function updateBgmButtons(playing) {
        const docList = [window.top.document];
        
        // iframeのドキュメントも追加
        const frames = window.top.frames;
        for (let i = 0; i < frames.length; i++) {
            try {
                if (frames[i] && frames[i].document) {
                    docList.push(frames[i].document);
                }
            } catch (e) {
                // 安全対策
            }
        }

        docList.forEach(doc => {
            const btnOn = doc.getElementById('bgm-on');
            const btnOff = doc.getElementById('bgm-off');
            if (btnOn && btnOff) {
                if (playing) {
                    btnOn.style.background = '#ff0055';
                    btnOn.style.color = '#ffffff';
                    btnOn.style.borderStyle = 'inset';
                    btnOff.style.background = '#0066cc';
                    btnOff.style.color = '#ffffff';
                    btnOff.style.borderStyle = 'outset';
                } else {
                    btnOn.style.background = '#0066cc';
                    btnOn.style.color = '#ffffff';
                    btnOn.style.borderStyle = 'outset';
                    btnOff.style.background = '#ff0055';
                    btnOff.style.color = '#ffffff';
                    btnOff.style.borderStyle = 'inset';
                }
            }
        });
    }

    // 初期化とグローバルエクスポート
    if (window === window.top) {
        window.bgmPlaying = false;
        window.playBgm = startBgm;
        window.stopBgm = stopBgm;
    } else {
        window.playBgm = function() {
            if (window.top && typeof window.top.playBgm === 'function') {
                window.top.playBgm();
            }
        };
        window.stopBgm = function() {
            if (window.top && typeof window.top.stopBgm === 'function') {
                window.top.stopBgm();
            }
        };
    }

    // ボタンの状態同期用
    window.addEventListener('load', function() {
        setTimeout(function() {
            updateBgmButtons(!!window.top.bgmPlaying);
        }, 100);
    });

})();
