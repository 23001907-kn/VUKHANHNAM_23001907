<script>
export default {
  data() {
    return {
      newTask: '',
      tasks: [],
      nextId: 1,
      activeFilter: 'all',
      filters: [
        { value: 'all', label: 'Tất cả' },
        { value: 'pending', label: 'Chưa hoàn thành' },
        { value: 'completed', label: 'Đã hoàn thành' },
      ],
      errorMessage: '',
      announcement: '',
    }
  },
  computed: {
    taskGroups() {
      const groups = { pending: [], completed: [] }
      for (const task of this.tasks) {
        groups[task.completed ? 'completed' : 'pending'].push(task)
      }
      return groups
    },
    totalTasks() {
      return this.tasks.length
    },
    remainingTasks() {
      return this.taskGroups.pending.length
    },
    completedTasks() {
      return this.totalTasks - this.remainingTasks
    },
    progress() {
      return this.totalTasks ? Math.round(this.completedTasks / this.totalTasks * 100) : 0
    },
    filteredTasks() {
      return this.activeFilter === 'all' ? this.tasks : this.taskGroups[this.activeFilter] ?? this.tasks
    },
    emptyTitle() {
      if (!this.totalTasks) return 'Một ngày mới, một khởi đầu mới'
      if (this.activeFilter === 'pending') return 'Bạn đã hoàn thành tất cả!'
      return 'Chưa có công việc hoàn thành'
    },
    emptyDescription() {
      if (!this.totalTasks) return 'Thêm công việc đầu tiên để bắt đầu ngày của bạn.'
      if (this.activeFilter === 'pending') return 'Không còn việc nào đang chờ. Hãy dành một chút thời gian nghỉ ngơi nhé.'
      return 'Đánh dấu một việc đã làm xong, việc đó sẽ xuất hiện tại đây.'
    },
  },
  methods: {
    addTask() {
      const name = this.newTask.trim()
      if (!name) {
        this.errorMessage = 'Vui lòng nhập tên công việc, không chỉ nhập khoảng trắng.'
        return
      }
      this.tasks.push({ id: this.nextId++, name, completed: false })
      this.newTask = ''
      this.errorMessage = ''
      this.announcement = `Đã thêm công việc: ${name}.`
      this.$refs.taskInput?.focus()
    },
    deleteTask(id) {
      this.tasks = this.tasks.filter(task => task.id !== id)
      this.announcement = 'Đã xóa công việc.'
    },
    clearCompleted() {
      const count = this.completedTasks
      this.tasks = this.tasks.filter(task => !task.completed)
      this.announcement = `Đã xóa ${count} công việc hoàn thành.`
    },
    updateStatus(task) {
      this.announcement = `${task.name}: ${task.completed ? 'đã hoàn thành' : 'chưa hoàn thành'}.`
    },
  },
}
</script>

<template>
  <div class="page-shell">
    <main id="main">
      <section class="overview" aria-label="Tổng quan công việc">
        <div class="stat"><span class="stat-icon" aria-hidden="true">≡</span><div><p>Tổng công việc</p><strong>{{ totalTasks }}</strong></div></div>
        <div class="stat"><span class="stat-icon pending-icon" aria-hidden="true">◷</span><div><p>Chưa hoàn thành</p><strong>{{ remainingTasks }}</strong></div></div>
        <div class="stat"><span class="stat-icon completed-icon" aria-hidden="true">✓</span><div><p>Đã hoàn thành</p><strong>{{ completedTasks }}</strong></div></div>
      </section>
      <div class="workspace">
        <section class="task-card" aria-labelledby="task-title">
          <div class="section-heading"><div><p class="eyebrow">TỪNG VIỆC NHỎ, TỪNG BƯỚC TIẾN</p><h2 id="task-title">Danh sách của bạn</h2></div><span class="list-badge">{{ totalTasks }} công việc</span></div>
          <!-- submit.prevent hỗ trợ cả nút Thêm và Enter, không tải lại trang. -->
          <form class="add-form" @submit.prevent="addTask" novalidate>
            <label class="sr-only" for="task-name">Tên công việc mới</label>
            <input id="task-name" ref="taskInput" v-model.trim="newTask" @input="errorMessage = ''" placeholder="Bạn muốn hoàn thành điều gì hôm nay?" maxlength="200" autocomplete="off" :aria-invalid="Boolean(errorMessage)" aria-describedby="task-input-hint" />
            <button type="submit" class="primary-button"><span aria-hidden="true">+</span> Thêm</button>
          </form>
          <p id="task-input-hint" class="input-hint" :class="{ error: errorMessage }">{{ errorMessage || 'Nhấn Enter hoặc chọn Thêm để tạo công việc mới.' }}</p>
          <div class="list-toolbar"><div class="filters" role="group" aria-label="Lọc công việc"><button v-for="filter in filters" :key="filter.value" type="button" :class="{ active: activeFilter === filter.value }" :aria-pressed="activeFilter === filter.value" @click="activeFilter = filter.value">{{ filter.label }}</button></div><span class="result-count">{{ filteredTasks.length }} việc</span></div>
          <ul v-if="filteredTasks.length" class="task-list" aria-label="Danh sách công việc">
            <li v-for="task in filteredTasks" :key="task.id" class="task-item" :class="{ completed: task.completed }">
              <label class="task-label"><input type="checkbox" v-model="task.completed" @change="updateStatus(task)" /><span class="task-name">{{ task.name }}</span></label>
              <button class="delete-button" type="button" @click.stop="deleteTask(task.id)" :aria-label="'Xóa công việc: ' + task.name" title="Xóa công việc"><svg aria-hidden="true" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7M14 10v7" /></svg></button>
            </li>
          </ul>
          <div v-else class="empty-state"><span class="empty-icon" aria-hidden="true">{{ totalTasks && activeFilter === 'pending' ? '✓' : '☷' }}</span><h3>{{ emptyTitle }}</h3><p>{{ emptyDescription }}</p></div>
          <div class="list-footer"><p><strong>{{ remainingTasks }}</strong> việc chưa hoàn thành</p><button type="button" class="clear-button" @click="clearCompleted" :disabled="completedTasks === 0">Xóa các việc đã hoàn thành</button></div>
        </section>
        <aside class="side-column">
          <section class="progress-card" aria-labelledby="progress-title"><span class="sparkle" aria-hidden="true">✦</span><p class="eyebrow">BẠN ĐANG LÀM RẤT TỐT</p><h2 id="progress-title">Từng bước một.</h2><p>Không cần làm tất cả cùng lúc. Hãy bắt đầu từ một việc nhỏ.</p><div class="progress-label"><span>Tiến độ hôm nay</span><strong>{{ progress }}%</strong></div><progress :value="completedTasks" :max="totalTasks || 1" aria-label="Tiến độ hoàn thành công việc">{{ progress }}%</progress><p class="progress-caption">{{ completedTasks }} / {{ totalTasks }} công việc đã hoàn thành</p></section>
        </aside>
      </div>
      <p class="sr-only" role="status" aria-live="polite">{{ announcement }}</p>
      
    </main>
  </div>
</template>
