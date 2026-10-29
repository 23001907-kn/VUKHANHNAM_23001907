<script>
import avatar from './assets/anhnen.jpg'
export default {
  data() {
    return {
      student: { id: '23001907', name: 'Vũ Khánh Nam', className: 'Công nghệ thông tin', avatar, average: 8.5, isStudying: true, introduction: 'Xin chào! Mình là Nam, sinh viên ngành Công nghệ thông tin. Mình yêu thích lập trình web, khám phá công nghệ và học hỏi những điều mới mỗi ngày.' },
      showIntroduction: true,
    }
  },
  computed: {
    classification() {
      const score = this.student.average
      if (typeof score !== 'number' || !Number.isFinite(score) || score < 0 || score > 10) return { label: 'Điểm chưa hợp lệ', color: 'neutral' }
      if (score >= 8) return { label: 'Giỏi', color: 'excellent' }
      if (score >= 6.5) return { label: 'Khá', color: 'good' }
      if (score >= 5) return { label: 'Trung bình', color: 'average' }
      return { label: 'Chưa đạt', color: 'poor' }
    },
    formattedAverage() {
      return this.classification.color === 'neutral' ? '—' : this.student.average.toLocaleString('vi-VN', { minimumFractionDigits: 1, maximumFractionDigits: 2 })
    },
  },
}
</script>

<template>
  <div class="page-shell">
    
    <main id="main">
      <div class="workspace">
        <article class="student-card" aria-label="Thẻ thông tin sinh viên">
          <div class="card-cover"><span>STUDENT PROFILE</span><span class="cover-mark" aria-hidden="true">✦</span></div>
          <div class="card-body">
            <div class="profile-top">
              <img class="avatar" v-bind:src="student.avatar" v-bind:alt="'Ảnh đại diện của ' + student.name" width="112" height="112" />
              <span class="status" :class="{ inactive: !student.isStudying }"><span class="status-dot"></span><span v-if="student.isStudying">Đang học</span><span v-else>Đã nghỉ</span></span>
            </div>
            <p class="profile-caption">XIN CHÀO, MÌNH LÀ</p>
            <h2>{{ student.name || 'Chưa có họ tên' }}</h2>
            <p class="student-id">Mã sinh viên <strong>{{ student.id || '—' }}</strong></p>
            <div class="class-row"><span class="detail-icon" aria-hidden="true">⌘</span><div><span class="field-caption">LỚP / NGÀNH HỌC</span><p v-text="student.className || 'Chưa có tên lớp'"></p></div></div>
            <div class="academic-panel">
              <div><span class="field-caption">ĐIỂM TRUNG BÌNH</span><p class="score">{{ formattedAverage }}<span> / 10</span></p></div>
              <div class="classification"><span class="field-caption">XẾP LOẠI</span><span class="grade" :class="classification.color" aria-live="polite">{{ classification.label }}</span></div>
            </div>
            <section class="introduction">
              <div class="section-heading"><h3>Đôi lời giới thiệu</h3><button type="button" class="text-button" @click="showIntroduction = !showIntroduction" :aria-expanded="showIntroduction" aria-controls="introduction-content">{{ showIntroduction ? 'Ẩn giới thiệu −' : 'Hiện giới thiệu +' }}</button></div>
              <p id="introduction-content" v-show="showIntroduction">{{ student.introduction }}</p>
            </section>
            
          </div>
        </article>
        
      </div>
      
    </main>
  </div>
</template>
