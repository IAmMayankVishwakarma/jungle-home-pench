# Resort Luxury Line Icons

21 individual SVG icons for the resort room amenities and features.

## Folders
- `amenities/` — 17 amenity icons
- `features/` — 4 room feature icons

## Style
- Transparent background
- Champagne gold stroke: `#B89A62`
- 64 × 64 viewBox
- SVG line art; scalable without pixelation

## Laravel installation
Copy the `amenities` and `features` folders into:

`public/assets/images/`

Example Blade:
```blade
<img src="{{ asset('assets/images/amenities/wifi.svg') }}"
     alt="Wi-Fi" width="24" height="24">
```

Feature example:
```blade
<img src="{{ asset('assets/images/features/jungle-view.svg') }}"
     alt="Jungle View" width="24" height="24">
```

## CSS
```css
.amenity-icon,
.feature-icon {
    width: 24px;
    height: 24px;
    object-fit: contain;
    flex: 0 0 auto;
}
```

The SVG stroke is currently `#B89A62`. To change the entire pack's gold tone,
replace that hex value in the SVG files.
