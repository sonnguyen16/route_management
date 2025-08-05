<template>
  <div ref="mapContainer" class="map-container w-full h-full"></div>
</template>

<script setup>
import mapboxgl from 'mapbox-gl'
import { onMounted, ref, watch } from 'vue'
import 'mapbox-gl/dist/mapbox-gl.css'

const props = defineProps({
  center: {
    type: Array,
    default: () => [107.242997, 10.495088]
  },
  zoom: {
    type: Number,
    default: 14
  },
  locations: {
    type: Array,
    default: () => []
  },
  plans: {
    type: Array,
    default: () => []
  },
  userRole: {
    type: String,
    default: 'captain'
  }
})

const emit = defineEmits(['map-click', 'map-ready'])

mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN

const mapContainer = ref()
let map = null
let userMarker = null
let locationHistory = []

onMounted(() => {
  initializeMap()
})

const initializeMap = () => {
  map = new mapboxgl.Map({
    container: mapContainer.value,
    style: 'mapbox://styles/mapbox/streets-v12',
    center: props.center,
    zoom: props.zoom
  })

  // Thêm điều khiển zoom và xoay
  map.addControl(new mapboxgl.NavigationControl(), 'bottom-right')

  // Khi map đã load xong
  map.on('load', () => {
    setupMapSources()
    setupMapLayers()
    setupMapEvents()

    // Load initial plans nếu có
    if (props.plans && props.plans.length > 0) {
      updatePlans(props.plans)
    }

    emit('map-ready', map)
  })
}

const setupMapSources = () => {
  // Load icon CSGT cho vị trí người dùng
  map.loadImage('/csgt.png', (error, image) => {
    if (error) {
      console.error('Lỗi load csgt.png:', error)
    } else if (!map.hasImage('csgt-icon')) {
      map.addImage('csgt-icon', image)
    }
  })

  // Load icon task cho plans
  map.loadImage('/Km3.png', (error, image) => {
    if (error) {
      console.error('Lỗi load task.png:', error)
    } else if (!map.hasImage('task-icon')) {
      map.addImage('task-icon', image)
    }
  })

  // Source cho vị trí người dùng
  map.addSource('user-location', {
    type: 'geojson',
    data: {
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [0, 0]
      },
      properties: {}
    }
  })

  // Source cho lịch sử di chuyển
  map.addSource('location-history', {
    type: 'geojson',
    data: {
      type: 'Feature',
      geometry: {
        type: 'LineString',
        coordinates: []
      },
      properties: {}
    }
  })

  // Source cho các đội trưởng (chỉ cho lãnh đạo)
  if (props.userRole === 'leader') {
    map.addSource('captains-locations', {
      type: 'geojson',
      data: {
        type: 'FeatureCollection',
        features: []
      }
    })
  }

  // Source cho plans
  map.addSource('plans', {
    type: 'geojson',
    data: {
      type: 'FeatureCollection',
      features: []
    }
  })
}

const setupMapLayers = () => {
  // Layer hiển thị vị trí người dùng với icon CSGT
  map.addLayer({
    id: 'user-location-point',
    type: 'symbol',
    source: 'user-location',
    layout: {
      'icon-image': 'csgt-icon',
      'icon-size': 0.2,
      'icon-allow-overlap': true,
      'icon-anchor': 'bottom'
    }
  })

  // Layer hiển thị lịch sử di chuyển
  map.addLayer({
    id: 'location-history-line',
    type: 'line',
    source: 'location-history',
    layout: {
      'line-join': 'round',
      'line-cap': 'round'
    },
    paint: {
      'line-color': '#3887be',
      'line-width': 3,
      'line-opacity': 0.8
    }
  })

  // Layer cho các đội trưởng (chỉ cho lãnh đạo) - Dùng icon CSGT thay vì chấm đỏ
  if (props.userRole === 'leader') {
    map.addLayer({
      id: 'captains-locations-points',
      type: 'symbol',
      source: 'captains-locations',
      layout: {
        'icon-image': 'csgt-icon',
        'icon-size': 0.2,
        'icon-allow-overlap': true,
        'icon-anchor': 'bottom'
      }
    })

    // Thêm đường tracking cho từng đội trưởng
    map.addLayer({
      id: 'captains-tracks',
      type: 'line',
      source: 'captains-locations',
      paint: {
        'line-color': ['get', 'color'],
        'line-width': 3,
        'line-opacity': 0.8
      },
      filter: ['has', 'track']
    })

    // Labels cho đội trưởng
    map.addLayer({
      id: 'captains-locations-labels',
      type: 'symbol',
      source: 'captains-locations',
      layout: {
        'text-field': ['get', 'name'],
        'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold'],
        'text-size': 12,
        'text-offset': [0, -2]
      },
      paint: {
        'text-color': '#ffffff',
        'text-halo-color': '#000000',
        'text-halo-width': 1
      }
    })
  }

  // Layer cho plans
  map.addLayer({
    id: 'plans-points',
    type: 'symbol',
    source: 'plans',
    layout: {
      'icon-image': 'task-icon',
      'icon-size': 0.8,
      'icon-allow-overlap': true,
      'icon-anchor': 'bottom'
    }
  })
}

const setupMapEvents = () => {
  // Click event cho map
  map.on('click', (e) => {
    emit('map-click', {
      lng: e.lngLat.lng,
      lat: e.lngLat.lat
    })
  })

  // Click event cho plans
  map.on('click', 'plans-points', (e) => {
    const plan = e.features[0].properties
    const formattedDate = formatDate(plan.date)
    const statusText = getStatusText(plan.status)

    new mapboxgl.Popup()
      .setLngLat(e.lngLat)
      .setHTML(
        `
        <div class="p-3 min-w-[200px]">
          <h4 class="font-bold text-lg mb-2">${plan.description}</h4>
          <p class="mb-1"><strong>📅 Ngày:</strong> ${formattedDate}</p>
          <p class="mb-1"><strong>⏰ Thời gian:</strong> ${plan.start_time} - ${plan.end_time}</p>
          <p><strong>📋 Trạng thái:</strong> <span class="px-2 py-1 rounded text-sm ${getStatusClass(
            plan.status
          )}">${statusText}</span></p>
        </div>
      `
      )
      .addTo(map)
  })

  // Click event cho captains (nếu là lãnh đạo)
  if (props.userRole === 'leader') {
    map.on('click', 'captains-locations-points', (e) => {
      const captain = e.features[0].properties

      // Format thời gian cập nhật
      const lastUpdateDate = new Date(captain.last_update)
      const formattedTime = lastUpdateDate.toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      })
      const formattedDate = lastUpdateDate.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      })

      new mapboxgl.Popup()
        .setLngLat(e.lngLat)
        .setHTML(
          `
          <div class="p-3 min-w-[200px]">
            <h4 class="font-bold text-lg mb-2">${captain.name}</h4>
            <p class="mb-1"><strong>📅 Ngày:</strong> ${formattedDate}</p>
            <p class="mb-1"><strong>⏰ Cập nhật:</strong> ${formattedTime}</p>
            <p class="mb-1"><strong>📍 Độ chính xác:</strong> ${captain.accuracy}m</p>
            <div class="mt-2 pt-2 border-t border-gray-200">
              <div class="flex items-center">
                <span class="w-3 h-3 rounded-full mr-2" style="background-color: ${captain.color}"></span>
                <span class="text-xs text-gray-600">Đường tracking</span>
              </div>
            </div>
          </div>
        `
        )
        .addTo(map)
    })
  }
}

// Utility functions
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('vi-VN', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusText = (status) => {
  switch (status) {
    case 'pending':
      return 'Chờ thực hiện'
    case 'active':
      return 'Đang thực hiện'
    case 'completed':
      return 'Hoàn thành'
    default:
      return status
  }
}

const getStatusClass = (status) => {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'active':
      return 'bg-green-100 text-green-800'
    case 'completed':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

// Update user location on map
const updateUserLocation = (longitude, latitude) => {
  if (map && map.getSource('user-location')) {
    map.getSource('user-location').setData({
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [longitude, latitude]
      },
      properties: {}
    })

    // Update location history
    locationHistory.push([longitude, latitude])
    if (locationHistory.length > 1) {
      map.getSource('location-history').setData({
        type: 'Feature',
        geometry: {
          type: 'LineString',
          coordinates: locationHistory
        },
        properties: {}
      })
    }
  }
}

// Update captains locations (for leaders)
const updateCaptainsLocations = (captains) => {
  if (props.userRole === 'leader' && map && map.getSource('captains-locations')) {
    // Lưu trữ vị trí cũ của các đội trưởng để vẽ đường tracking
    const captainTracks = {}

    // Nếu đã có dữ liệu trước đó, lấy ra để cập nhật
    if (map.getSource('captains-locations')._data && map.getSource('captains-locations')._data.features) {
      map.getSource('captains-locations')._data.features.forEach((feature) => {
        if (feature.properties.track) {
          captainTracks[feature.properties.user_id] = feature.properties.track
        }
      })
    }

    // Tạo màu ngẫu nhiên cho mỗi đội trưởng nếu chưa có
    const captainColors = {}
    const colors = ['#FF5733', '#33FF57', '#3357FF', '#F033FF', '#FF33A8', '#33FFF6']

    const features = captains.map((captain, index) => {
      // Lấy hoặc tạo track cho đội trưởng này
      const trackCoords = captainTracks[captain.user_id] || []

      // Thêm vị trí hiện tại vào track (nếu khác vị trí cuối cùng)
      const currentCoord = [captain.location.lng, captain.location.lat]
      if (
        trackCoords.length === 0 ||
        trackCoords[trackCoords.length - 1][0] !== currentCoord[0] ||
        trackCoords[trackCoords.length - 1][1] !== currentCoord[1]
      ) {
        trackCoords.push(currentCoord)
      }

      // Giới hạn số điểm trong track để tránh quá tải
      const maxTrackPoints = 100
      if (trackCoords.length > maxTrackPoints) {
        trackCoords.splice(0, trackCoords.length - maxTrackPoints)
      }

      // Lấy hoặc tạo màu cho đội trưởng
      if (!captainColors[captain.user_id]) {
        captainColors[captain.user_id] = colors[index % colors.length]
      }

      return {
        type: 'Feature',
        geometry: {
          type: 'Point',
          coordinates: currentCoord
        },
        properties: {
          user_id: captain.user_id,
          name: captain.user_name,
          last_update: captain.location.recorded_at,
          accuracy: captain.location.accuracy,
          track: trackCoords,
          color: captainColors[captain.user_id]
        }
      }
    })

    // Thêm features cho đường tracking
    const trackFeatures = captains
      .map((captain, index) => {
        const trackCoords = captainTracks[captain.user_id] || []
        if (trackCoords.length > 1) {
          return {
            type: 'Feature',
            geometry: {
              type: 'LineString',
              coordinates: trackCoords
            },
            properties: {
              user_id: captain.user_id,
              color: captainColors[captain.user_id] || colors[index % colors.length],
              track: trackCoords
            }
          }
        }
        return null
      })
      .filter(Boolean)

    map.getSource('captains-locations').setData({
      type: 'FeatureCollection',
      features: [...features, ...trackFeatures]
    })
  }
}

// Update plans on map
const updatePlans = (plans) => {
  console.log('Updating plans on map:', plans)
  if (map && map.getSource('plans')) {
    const features = plans.map((plan) => ({
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [parseFloat(plan.lng), parseFloat(plan.lat)]
      },
      properties: {
        id: plan.id,
        description: plan.description,
        date: plan.date,
        start_time: plan.start_time,
        end_time: plan.end_time,
        status: plan.status
      }
    }))

    console.log('Plan features:', features)
    map.getSource('plans').setData({
      type: 'FeatureCollection',
      features
    })
  }
}

// Fly to location
const flyTo = (coordinates, zoom = 16) => {
  if (map) {
    map.flyTo({
      center: coordinates,
      zoom
    })
  }
}

// Watch for prop changes
watch(() => props.locations, updateCaptainsLocations, { deep: true })
watch(() => props.plans, updatePlans, { deep: true })

// Expose methods
defineExpose({
  updateUserLocation,
  updateCaptainsLocations,
  updatePlans,
  flyTo,
  map: () => map
})
</script>

<style scoped>
.map-container {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
</style>
