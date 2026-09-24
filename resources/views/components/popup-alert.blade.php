@php $popup = session('popup'); @endphp

@if ($popup)
    <div class="toast-alert toast-alert-{{ $popup['type'] ?? 'success' }}" id="toastAlert">

        <div class="toast-alert-icon">
            @if (($popup['type'] ?? 'success') === 'warning')
                <i class="bi bi-exclamation-triangle-fill"></i>
            @else
                <i class="bi bi-check-circle-fill"></i>
            @endif
        </div>

        <div class="toast-alert-text">
            <strong>{{ $popup['title'] }}</strong>
            <span>{{ $popup['message'] }}</span>
        </div>

    </div>

    <style>

        @keyframes toastIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes toastOut {
            from { opacity: 1; transform: translateY(0); }
            to   { opacity: 0; transform: translateY(-10px); }
        }

        .toast-alert {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 9999;

            display: flex;
            align-items: flex-start;
            gap: 10px;

            max-width: 320px;

            padding: 12px 16px;

            border-radius: 8px;

            background: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);

            font-family: 'Poppins', sans-serif;

            animation: toastIn 0.25s ease;
        }

        .toast-alert.toast-hide {
            animation: toastOut 0.25s ease forwards;
        }

        .toast-alert-icon {
            font-size: 16px;
            margin-top: 1px;
        }

        .toast-alert-success .toast-alert-icon { color: #16a34a; }
        .toast-alert-warning .toast-alert-icon { color: #d97706; }

        .toast-alert-success { border-left: 3px solid #16a34a; }
        .toast-alert-warning { border-left: 3px solid #d97706; }

        .toast-alert-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .toast-alert-text strong {
            font-size: 11px;
            font-weight: 700;
            color: #222;
        }

        .toast-alert-text span {
            font-size: 9.5px;
            line-height: 1.4;
            color: #666;
        }

    </style>

    <script>
        (function () {
            var toast = document.getElementById('toastAlert');
            if (! toast) return;

            setTimeout(function () {
                toast.classList.add('toast-hide');
                setTimeout(function () {
                    toast.remove();
                }, 250);
            }, 3000);
        })();
    </script>
@endif
