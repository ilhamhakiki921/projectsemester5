@php $popup = session('popup'); @endphp

@if ($popup)
    <div class="popup-alert-overlay" id="popupAlertOverlay">
        <div class="popup-alert-box popup-alert-{{ $popup['type'] ?? 'success' }}">

            <div class="popup-alert-icon">
                @if (($popup['type'] ?? 'success') === 'warning')
                    <i class="bi bi-exclamation-triangle-fill"></i>
                @else
                    <i class="bi bi-check-circle-fill"></i>
                @endif
            </div>

            <h3 class="popup-alert-title">{{ $popup['title'] }}</h3>
            <p class="popup-alert-message">{{ $popup['message'] }}</p>

            <button
                type="button"
                class="popup-alert-btn"
                onclick="document.getElementById('popupAlertOverlay').remove()"
            >
                Oke, Mengerti
            </button>

        </div>
    </div>

    <style>

        .popup-alert-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(0, 0, 0, 0.45);

            font-family: 'Poppins', sans-serif;
        }

        .popup-alert-box {
            width: 320px;

            background: #ffffff;
            border-radius: 12px;

            padding: 28px 24px 22px;

            text-align: center;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .popup-alert-icon {
            font-size: 34px;
            margin-bottom: 10px;
        }

        .popup-alert-success .popup-alert-icon {
            color: #16a34a;
        }

        .popup-alert-warning .popup-alert-icon {
            color: #d97706;
        }

        .popup-alert-title {
            margin-bottom: 6px;

            font-size: 14px;
            font-weight: 700;
            color: #222;
        }

        .popup-alert-message {
            margin-bottom: 18px;

            font-size: 11px;
            line-height: 1.5;
            color: #555;
        }

        .popup-alert-btn {
            height: 34px;

            padding: 0 22px;

            border: none;
            border-radius: 6px;

            color: #ffffff;

            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;

            cursor: pointer;
        }

        .popup-alert-success .popup-alert-btn {
            background: #16a34a;
        }

        .popup-alert-warning .popup-alert-btn {
            background: #d97706;
        }

    </style>
@endif
