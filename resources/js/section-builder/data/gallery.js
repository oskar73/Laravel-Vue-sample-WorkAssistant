export default {
  data: {
    items: [
      {
        title: 'Item title',
        src: '/images/sample/1.jpg'
      },
      {
        title: 'Item title',
        src: '/images/sample/2.jpg'
      },
      {
        title: 'Item title',
        src: '/images/sample/3.jpg'
      }
    ],
    elements: {
      title: 'Gallery',
      subtitle: 'Our latest and best photos',
      description: 'We love to take pictures and show them to the world'
    }
  },
  setting: {
    layout: 1,
    columns: [3, 4, 5, 6],
    column: 3,
    elements: {
      title: true,
      subtitle: true,
      description: true
    },
    layouts: {
      fullWidth: false,
      sectionSize: 'M',
      shadow: true,
      contentAlignment: 'left',
      shape: 'square',
      spacing: true
    },
    items: {
      title: true
    }
  },
  background: {
    colors: ['#5ae3aa', '#d2e7f2', '#243037', '#ffffff'],
    color: '#d2e7f2',
    image: null,
    video: null,
    pattern: null
  }
}
