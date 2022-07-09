<template>
  <div :class="[tip == 'no-auth' ? '' : 'container']" class="footer">
    <footer class="py-3">
      <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <li class="nav-item">
          <a v-if="tip !== 'no-auth'" href="/" class="nav-link px-2 text-muted"
            >Home</a
          >
        </li>
        <li class="nav-item">
          <a href="/contact" class="nav-link px-2 text-muted">Contact</a>
        </li>
        <li class="nav-item">
          <a href="/StudBud/about-us.php#about" class="nav-link px-2 text-muted"
            >Features</a
          >
        </li>
        <li class="nav-item">
          <a href="/StudBud/about-us.php" class="nav-link px-2 text-muted"
            >About</a
          >
        </li>
      </ul>
      <p class="text-center text-muted">&copy; 2022 StudBud</p>
    </footer>
  </div>
</template>

<script>
export default {
  props: ["tip"],
};
</script>